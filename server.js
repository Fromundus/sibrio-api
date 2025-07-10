import { createServer } from 'http';
import { Server } from 'socket.io';
import express from 'express';

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "*", // Adjust this to your frontend's URL
        methods: ["GET", "POST"]
    }
});

app.use(express.json()); // For parsing application/json

app.post('/signal', (req, res) => {
    const { action } = req.body;
    console.log('Received action:', action);
    io.emit('signal', { action }); // Emit the signal to all connected clients
    res.sendStatus(200);
});

io.on('connection', (socket) => {
    console.log('A user connected');

    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});

server.listen(4000, '0.0.0.0', () => {
    console.log('Listening on *:4000');
});
