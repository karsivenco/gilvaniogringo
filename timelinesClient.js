// timelinesClient.js
const TIMELINES_API = "https://api.timelines.ai/v1";
const TIMELINES_TOKEN = "ea8f1c30-23ad-438d-9fcf-a28942f0b413"; // seu token real

async function fetchChats() {
  const res = await fetch(`${TIMELINES_API}/chats`, {
    headers: { "Authorization": `Bearer ${TIMELINES_TOKEN}` }
  });
  return res.json();
}

async function fetchMessages(chatId) {
  const res = await fetch(`${TIMELINES_API}/chats/${chatId}/messages`, {
    headers: { "Authorization": `Bearer ${TIMELINES_TOKEN}` }
  });
  return res.json();
}

async function sendMessage(chatId, body) {
  const res = await fetch(`${TIMELINES_API}/chats/${chatId}/send_message`, {
    method: "POST",
    headers: {
      "Authorization": `Bearer ${TIMELINES_TOKEN}`,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ body })
  });
  return res.json();
}
