# Snippets
This repository contains random code scripts and snippets that may or may not be useful. Functions and scripts are typically added when I find myselfneeding them across different projects. This keeps them together.

# php
- csv-merge.php
  A singe-file PHP website that can merge multiple CSV files together. This can be placed directly in any PHP capable server

# python
- csv_combine.py
  A python function to merge all CSVs in a directory
- csv_dict-to-csv.py
  A function to convert a dictionary to a CSV, this function supports a dictionary where each key is a sub dictionary and writes it out to a CSV

# wordpress
- admin_settings.php
  A dynamic wordpress admin settings page

# Github
## Workflows
- zip-to-release
  Zips the entire repo on a push to main and adds it to a release. Release notes are the details of the triggering commit, and the tag/version is pulled from the repo as long as a file contains the string Version: 0.0.0 This was orginally built for wordpress plugins, but shouls be portable
- mkdocs-build-and-deploy
  Builds and zips a repo containing an mkdocs site. It performs some basic processing if configured in mkdocs like link conversion