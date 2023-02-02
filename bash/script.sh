#!/bin/bash

if [ $# -eq 0 ]; then
  echo "Error: No output file name provided."
  exit 1
fi

output_file=$1

output=$(./stockfish/stockfish.exe << EOF

position startpos moves e2e4 e7e5
eval
go movetime 5
EOF
)
echo "$output" > "$output_file"
