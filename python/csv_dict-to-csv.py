# Dict to CSV
# A function to convert a dictionary to a CSV, this function supports a dictionary where each key is a sub dictionary and writes it out to a CSV

# Requires: csv, os

# usage: dictToCsv(dataset, name, outputDir)
# dataset = dictionary to be converted to CSV
# name = name of the CSV to be saved
# outputDir = directory to save the CSV
# returns: path to CSV

# example: dictToCsv(dataset, "dictToCsv", "C:/Users/username/Desktop/csvs/")
# returns: "C:/Users/username/Desktop/csvs/dictToCsv.csv"


import csv, os

def dictToCsv(dataset, name, outputDir):
    os.makedirs(outputDir, exist_ok=True)
    # build the headers
    headers = []
    for key in dataset:
        for k in dataset[key]:
            if k not in headers:
                headers.append(k)
    # build the rows
    rows = []
    for key in dataset:
        row = []
        for h in headers:
            try:
                row.append(dataset[key][h])
            except:
                row.append(None)
        rows.append(row)
    # write the CSV
    with open(outputDir + "/" + name + ".csv", "w", encoding="UTF-8") as file:
        writer = csv.writer(file, delimiter=",")
        writer.writerow(headers)
        writer.writerows(rows)
    return outputDir + "/" + name + ".csv"