# The main script behind solarwichtel.de
# It looks for new files from opendata.dwd.de mounted at /mnt/dwd_opendata
# For every shelly name, it retrieves the current radiation intensity and writes it to a
# csv file at /mnt/ramdisk. This is used by the web page to preview solar power.
# Every shelly topic currently activated gets a MQTT message to turn on or off based on the threshold.


import netCDF4 as nc
import os
import time
import requests
import paho.mqtt.client as mqtt
import configparser

BROKER_ADDRESS = "localhost"
PORT = 1883
QOS = 1


config = configparser.ConfigParser()
config.read('solarwichtel_config.ini')
threshold = int(config['radiation']['threshold'])
print("Threshold is", threshold)


# ChatGPT!
# This function uses either the lat or long field from the nc file.
# It takes a coordinate and searches the array index closest to this coordinate.
# We use this to find the place to look at in the image for each shelly point.
# This is very slow to do for 16k zipcodes, so it is executed once on start and saved in a lookup table 
# ds_name is either 'lat' or 'lon'
# target is the latitude or longitude as float
# this is neither the ideal algorithm choice nor pretty optimized, but it works and only ran once at start (2 * 16k times)
def find_closest_index(ds_name, target):
    global ds
    sorted_list = list(ds[ds_name][:])
    low = 0
    high = len(sorted_list) - 1

    while low <= high:
        mid = (low + high) // 2
        if sorted_list[mid] == target:
            return mid
        elif sorted_list[mid] < target:
            low = mid + 1
        else:
            high = mid - 1

    # After the loop, low and high pointers will be adjacent
    # Now, we find the closest number
    if high < 0:
        return low
    elif low >= len(sorted_list):
        return high
    elif abs(sorted_list[low] - target) < abs(sorted_list[high] - target):
        return low
    else:
        return high

# this function takes a string like "solarwichtel_5140_1390" and returns the latutude and longitude
# in this example 51.4 and 13.9
def shellyname_to_latlon(name):
    lat = int(name[13:17]) / 100
    lon = int(name[18:]) / 100

    return lat, lon

# this function takes a pair of latitude and longitude and calls find_closest_index on both of them
def latlon_to_index(lat, lon):
    # latitude: 57 - 46
    # longitude: 5 - 16
    # output pixels: 0 - 221
    y = find_closest_index('lat', lat)
    x = find_closest_index('lon', lon)
    return y, x

# Called every minute. This function retrieves the dwd file list and checks if there's a new file
# since the last call. If yes, it returns the file name (with path), else None.
newest_file = ""
def check_new_file():
    global directory, newest_file

    names = os.listdir(directory)
    names = [file for file in names if file.startswith("SISin") and file.endswith("DEv3.nc")]

    # the file names have a timestamp, after sorting the newest one ist always last
    file = directory + '/' + sorted(names)[-1]

    if file == newest_file:
        return None
    else:
        newest_file = file
        return file


# Checks if the provided radiation is above the threshold and sends either "on" or "off" signal
# to the corresponding MQTT topic
def send_mqtt(shelly, radiation):
    status = radiation > threshold

    print("MQTT", shelly, radiation, "=>", status)

    DATA = '{"id":0, "src":"user1", "method":"Switch.Set", "params":{"id":0,"on":' + ('true' if status else 'false') +  '}}'
    topic = shelly + "/rpc"
    print("Publish:", topic, DATA)
    client.publish(topic, DATA, qos=QOS)




client = mqtt.Client(client_id="python-" + str(os.getpid()))
client.username_pw_set("[user]", "[password]")
client.connect(BROKER_ADDRESS, PORT)

print("Connected to MQTT Broker: " + BROKER_ADDRESS)
client.loop_start()


directory = '/mnt/dwd_opendata/weather/satellite/radiation/sis'


# get list of all 16k shelly names
# zipcode_shelles is a quick database export containing one shelly name in each line
with open("zipcode_shellies.txt", "r") as f:
    zipcode_shellies = f.readlines()
zipcode_shellies = [z.strip() for z in zipcode_shellies]
zipcode_shellies = list(set(zipcode_shellies))
zipcode_index = None #2d array that stores the x and y coordinate for each shelly. This is computed on first run

while True:
    if check_new_file():
        print(newest_file)

        with open("/mnt/ramdisk/zipcode_status.csv", "w") as f:
            ds = nc.Dataset(newest_file)

            # We need to generate the index lookup, but this is only possible after opening a file. It takes about 20 seconds.
            if not zipcode_index:
                before = time.time()
                zipcode_index = []
                for shelly in zipcode_shellies:
                    lat, lon = shellyname_to_latlon(shelly)
                    y, x = latlon_to_index(lat, lon)
                    zipcode_index.append([y, x])

                print("Took", int(time.time() - before), "sec to generate index table")


            # get list of active shelly names - PHP reads this from the database
            # This is retrieved on each run as new shellys could have been registered since the last restart.
            url = "http://localhost/active_shellies.php"
            req = requests.get(url)
            active_shellies = req.text.split(",")[:-1]
            print(active_shellies)


            for i, shelly in enumerate(zipcode_shellies):
                y = zipcode_index[i][0]
                x = zipcode_index[i][1]
                radiation = int(ds['SIS'][0, y, x])

                f.write(shelly + ";" + str(radiation) + "\n")
                
                if shelly in active_shellies:
                    send_mqtt(shelly, radiation)

            ds.close()
    else:
        print("No new file")

    time.sleep(60)
