#!/home/napoleon/.PythonEnvs/CommunicationsWeb/bin/python3
"""CIR chat server using websockets."""
import asyncio
import websockets
from websockets.asyncio.server import serve
import argparse

def checkArguments():
    """Check program arguments and return program parameters."""
    parser = argparse.ArgumentParser()
    parser.add_argument('-i', '--ip', default='0.0.0.0',
                        help='websockets server host / ip')
    parser.add_argument('-p', '--port', default=8080,
                        help='websockets server port')
    parser.add_argument('-v', '--verbose', action='store_false',
                        help='verbose mode')
    return parser.parse_args()

# --- NOUVEAU : liste globale pour stocker l’historique ---
history = []

async def clientHandler(websocket):
    clients.add(websocket)
    # Rejoue l’historique
    for msg in history:
        await websocket.send(msg)

    if args.verbose:
        print(f"{websocket.remote_address} open connection")

    try:
        async for message in websocket:
            if args.verbose:
                print(f"{websocket.remote_address} Message received: \"{message}\"")
            history.append(message)
            # Diffuse à tous les clients, en ignorant ceux qui ont déjà fermé la connexion
            for client in list(clients):
                try:
                    await client.send(message)
                except (websockets.ConnectionClosedOK, websockets.ConnectionClosedError):
                    # on retire les clients fermés
                    clients.remove(client)

    except websockets.ConnectionClosed:
        pass

    finally:
        # assure-toi de ne plus conserver la connexion dans le set
        clients.discard(websocket)
        if args.verbose:
            print(f"{websocket.remote_address} close connection")



async def main():
    """Main server loop."""
    async with serve(clientHandler, args.ip, args.port) as server:
        print(f'WebSockets server launch: {args.ip}:{args.port}')
        await server.serve_forever()

# Entry point of the program.
clients = set()
args = checkArguments()
if args.ip == 'localhost':
    print('Warning: use real ip instead of localhost for external connections')
asyncio.run(main())