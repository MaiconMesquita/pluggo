import React from 'react';
import CadastroForm from './components/CadastroForm'; // Importa o formulário que criamos
import './App.css';

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <h1>EcoFlow Manager</h1>
        <CadastroForm /> {/* Usa o formulário aqui */}
      </header>
    </div>
  );
}

export default App;
