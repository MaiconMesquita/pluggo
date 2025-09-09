import express from 'express';

const app = express();
const port = 3333;

app.use(express.json());

app.get('/', (req, res) => {
	res.json({ message: 'API EcoFlow Manager está no ar!' });
});

app.listen(port, () => {
	console.log(`🚀 Servidor backend rodando em http://localhost:${port}`);
});
