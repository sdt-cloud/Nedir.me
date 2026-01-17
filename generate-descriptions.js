/**
 * Nedir.me - ChatGPT Kavram Açıklama Oluşturucu
 * 
 * Bu script WordPress'teki kavramları alır ve ChatGPT ile açıklama oluşturur.
 * 
 * Kullanım: node generate-descriptions.js
 */

require('dotenv').config();
const https = require('https');

// Konfigürasyon
const OPENAI_API_KEY = process.env.OPENAI_API_KEY;
const WP_BASE_URL = 'http://localhost:8881';
const MODEL = 'gpt-4o-mini'; // veya 'gpt-3.5-turbo'
const DELAY_BETWEEN_REQUESTS = 1000; // ms

// WordPress REST API helper
async function wpRequest(endpoint, method = 'GET', body = null) {
    return new Promise((resolve, reject) => {
        const url = new URL(`${WP_BASE_URL}/wp-json/wp/v2/${endpoint}`);
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
            },
        };

        const req = require('http').request(url, options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try {
                    resolve(JSON.parse(data));
                } catch (e) {
                    resolve(data);
                }
            });
        });

        req.on('error', reject);
        if (body) req.write(JSON.stringify(body));
        req.end();
    });
}

// ChatGPT API helper
async function generateDescription(kavramTitle, category) {
    return new Promise((resolve, reject) => {
        const prompt = `Sen Nedir.me için içerik yazarısın. "${kavramTitle}" kavramı için Türkçe açıklama yaz.

Kategori: ${category}

Format:
1. KISA TANIM (1 cümle, max 20 kelime)
2. AÇIKLAMA (2-3 paragraf, sade ve anlaşılır dil)
3. GÜNLÜK HAYAT ÖRNEĞİ (1 paragraf)

Kurallar:
- Sade, anlaşılır Türkçe kullan
- Herkesin anlayabileceği şekilde yaz
- Teknik terimleri açıkla
- Emoji kullanma
- Başlık veya markdown formatı kullanma, düz metin yaz

Cevabını JSON formatında ver:
{
  "short_def": "Kısa tanım buraya",
  "content": "Ana açıklama buraya",
  "example": "Günlük hayat örneği buraya"
}`;

        const postData = JSON.stringify({
            model: MODEL,
            messages: [
                { role: 'system', content: 'Sen eğitici içerik üreten bir asistansın. Cevaplarını her zaman geçerli JSON formatında ver.' },
                { role: 'user', content: prompt }
            ],
            temperature: 0.7,
            max_tokens: 1000,
        });

        const options = {
            hostname: 'api.openai.com',
            port: 443,
            path: '/v1/chat/completions',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${OPENAI_API_KEY}`,
                'Content-Length': Buffer.byteLength(postData),
            },
        };

        const req = https.request(options, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                try {
                    const response = JSON.parse(data);
                    if (response.error) {
                        reject(new Error(response.error.message));
                        return;
                    }
                    const content = response.choices[0].message.content;
                    // JSON parse et
                    const jsonMatch = content.match(/\{[\s\S]*\}/);
                    if (jsonMatch) {
                        resolve(JSON.parse(jsonMatch[0]));
                    } else {
                        resolve({ short_def: '', content: content, example: '' });
                    }
                } catch (e) {
                    reject(e);
                }
            });
        });

        req.on('error', reject);
        req.write(postData);
        req.end();
    });
}

// Delay helper
function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// Ana fonksiyon
async function main() {
    console.log('🚀 Nedir.me Kavram Açıklama Oluşturucu');
    console.log('=====================================\n');

    if (!OPENAI_API_KEY) {
        console.error('❌ OPENAI_API_KEY bulunamadı! .env dosyasını kontrol edin.');
        process.exit(1);
    }

    try {
        // Tüm kavramları al (sayfalama ile)
        let allKavramlar = [];
        let page = 1;
        let hasMore = true;

        console.log('📥 Kavramlar WordPress\'ten alınıyor...');

        while (hasMore) {
            const kavramlar = await wpRequest(`kavram?per_page=100&page=${page}&status=publish`);
            if (Array.isArray(kavramlar) && kavramlar.length > 0) {
                allKavramlar = allKavramlar.concat(kavramlar);
                page++;
            } else {
                hasMore = false;
            }
        }

        console.log(`✅ ${allKavramlar.length} kavram bulundu.\n`);

        // Açıklaması olmayan kavramları filtrele
        const kavramlarToProcess = allKavramlar.filter(k => {
            const content = k.content?.rendered || '';
            return content.trim() === '' || content.includes('Açıklama eklenecek');
        });

        console.log(`📝 ${kavramlarToProcess.length} kavramın açıklaması oluşturulacak.\n`);

        if (kavramlarToProcess.length === 0) {
            console.log('✅ Tüm kavramların açıklaması mevcut!');
            return;
        }

        // Her kavram için açıklama oluştur
        let processed = 0;
        let errors = 0;

        for (const kavram of kavramlarToProcess) {
            const title = kavram.title.rendered;

            try {
                process.stdout.write(`[${processed + 1}/${kavramlarToProcess.length}] ${title}... `);

                // Kategori bilgisini al
                const category = kavram['ana-kategori']?.[0] || 'genel';

                // ChatGPT'den açıklama al
                const description = await generateDescription(title, category);

                // WordPress'e kaydet (burada WP REST API update kullanılacak)
                // Not: WP REST API authentication gerektirir, şimdilik sadece konsola yazdırıyoruz

                console.log('✅');
                console.log(`   Kısa: ${description.short_def?.substring(0, 50)}...`);

                processed++;

                // Rate limiting
                await delay(DELAY_BETWEEN_REQUESTS);

            } catch (error) {
                console.log('❌');
                console.error(`   Hata: ${error.message}`);
                errors++;
            }
        }

        console.log('\n=====================================');
        console.log(`✅ Tamamlanan: ${processed}`);
        console.log(`❌ Hatalar: ${errors}`);

    } catch (error) {
        console.error('❌ Genel hata:', error.message);
    }
}

main();
