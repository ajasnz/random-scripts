# CSV Combiner

# Combines all CSVs in a directory into one CSV

# Requires: csv, os, glob, pandas

# All CSVs to be combined should be in one directory

# usage: combineCsvs(dir, name)
# dir = directory of CSVs to combine
# name = name of the CSV to be saved
# returns: path to combined CSV

# example: combineCsvs("C:/Users/username/Desktop/csvs/", "combinedCSV")
# returns: "C:/Users/username/Desktop/csvs/combinedCSV.csv"




import csv, os, glob
import pandas as pd
def combineCsvs(dir, name):
    origDir = os.getcwd()
    os.chdir(dir)
    allCsvs = [c for c in glob.glob("*.{}".format("csv"))]
    csvCombiner = pd.concat([pd.read_csv(f) for f in allCsvs])
    combinedCsvName = f"{name}.csv"
    csvCombiner.to_csv(combinedCsvName, index=False, encoding="UTF-8")
    os.chdir(origDir)
    combinedFilePath = dir + combinedCsvName
    return combinedFilePath
