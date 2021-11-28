#!/usr/bin/python3

import argparse
import subprocess

parser = argparse.ArgumentParser(description='Bot builder.')

parser.add_argument('filename', help='File name')

args = parser.parse_args()

print(args)
steps = [
    'mv .env .env.tmp',
    f'zip -r {args.filename} .',
    'mv .env.tmp .env'
]

for step in steps:
    subprocess.run(step, shell=True, cwd='.')