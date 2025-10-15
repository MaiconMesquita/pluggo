import React, { useState } from 'react';

// Define os tipos de dados que o formulário vai ter
interface FormData {
  nome: string;
  email: string;
  setor: string;
}

const CadastroForm: React.FC = () => {
  // Estado para guardar os dados do formulário
  const [formData, setFormData] = useState<FormData>({
    nome: '',
    email: '',
    setor: '',
  });

  // Função para atualizar o estado quando o usuário digita
  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prevState => ({
      ...prevState,
      [name]: value,
    }));
  };

  // Função para lidar com o envio do formulário
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Aqui é onde você enviaria os dados para o back-end
    console.log('Dados do formulário para enviar:', formData);
    alert(`Cadastro de ${formData.nome} enviado com sucesso!`);
    // Limpar o formulário após o envio
    setFormData({ nome: '', email: '', setor: '' });
  };

  return (
    <div style={{ maxWidth: '400px', margin: '2rem auto', padding: '1rem', border: '1px solid #ccc', borderRadius: '8px' }}>
      <h2>Cadastro de Novo Usuário</h2>
      <form onSubmit={handleSubmit}>
        <div style={{ marginBottom: '1rem' }}>
          <label htmlFor="nome">Nome:</label>
          <input
            type="text"
            id="nome"
            name="nome"
            value={formData.nome}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', boxSizing: 'border-box' }}
          />
        </div>
        <div style={{ marginBottom: '1rem' }}>
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', boxSizing: 'border-box' }}
          />
        </div>
        <div style={{ marginBottom: '1rem' }}>
          <label htmlFor="setor">Setor:</label>
          <select
            id="setor"
            name="setor"
            value={formData.setor}
            onChange={handleChange}
            required
            style={{ width: '100%', padding: '8px', boxSizing: 'border-box' }}
          >
            <option value="">Selecione um setor</option>
            <option value="Administrativo">Administrativo</option>
            <option value="Produção">Produção</option>
            <option value="Logística">Logística</option>
          </select>
        </div>
        <button type="submit" style={{ width: '100%', padding: '10px', backgroundColor: '#007bff', color: 'white', border: 'none', borderRadius: '4px' }}>
          Cadastrar
        </button>
      </form>
    </div>
  );
};

export default CadastroForm;
