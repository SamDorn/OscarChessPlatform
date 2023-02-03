import subprocess

def main(output_file):
    

    p = subprocess.Popen(['./stockfish/stockfish.exe position startpos', 'eval'],
                        stdin=subprocess.PIPE,
                        stdout=subprocess.PIPE,
                        stderr=subprocess.PIPE)

    output, error = p.communicate(input=b'\neval\n')

    with open(output_file, 'w') as file:
        file.write(output.decode())

if __name__ == '__main__':
    output_file = input("Enter output file name: ")
    main(output_file)
