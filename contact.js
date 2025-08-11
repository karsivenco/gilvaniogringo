const { Client } = require('whatsapp-web.js');
const express = require('express');
const app = express();
const client = new Client();

client.initialize();

app.get('/api/contacts', async (req, res) => {
  try {
    const contacts = await client.getContacts(); // retorna array de Contact
    // Você pode filtrar ou formatar o retorno para enviar só dados necessários
    const contatosFormatados = contacts.map(c => ({
      id: c.id._serialized,
      nome: c.name || c.pushname || '',
      telefone: c.number,
      isBusiness: c.isBusiness,
      isBlocked: c.isBlocked,
      // etc...
    }));
    res.json(contatosFormatados);
  } catch (error) {
    res.status(500).json({ error: 'Erro ao buscar contatos' });
  }
});

app.listen(3000, () => console.log('API rodando na porta 3000'));
