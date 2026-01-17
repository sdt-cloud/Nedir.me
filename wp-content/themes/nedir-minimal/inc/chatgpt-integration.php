<?php
/**
 * Nedir.me - Gemini AI Kavram Açıklama Oluşturucu
 * WordPress admin'den çalışır
 */

if (!defined('ABSPATH')) exit;

// Admin menüsüne ekle
add_action('admin_menu', 'nedir_add_ai_menu');
function nedir_add_ai_menu() {
    add_submenu_page(
        'edit.php?post_type=kavram',
        'AI Açıklama Oluştur',
        '🤖 AI Açıklama',
        'manage_options',
        'generate-descriptions',
        'nedir_ai_page'
    );
}

function nedir_ai_page() {
    $api_key = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
    
    if (empty($api_key)) {
        echo '<div class="wrap"><h1>Gemini AI Açıklama Oluşturucu</h1>';
        echo '<div class="notice notice-error"><p>GEMINI_API_KEY tanımlı değil!</p>';
        echo '<p>Google AI Studio\'dan ücretsiz API key alın: <a href="https://makersuite.google.com/app/apikey" target="_blank">https://makersuite.google.com/app/apikey</a></p>';
        echo '</div></div>';
        return;
    }
    
    ?>
    <div class="wrap">
        <h1>🤖 Gemini AI Kavram Açıklama Oluşturucu</h1>
        
        <?php
        if (isset($_POST['start_generation']) && wp_verify_nonce($_POST['_wpnonce'], 'generate_descriptions')) {
            nedir_process_batch_gemini();
        }
        ?>
        
        <div class="card" style="max-width: 600px; padding: 20px;">
            <h2>Kavram Durumu</h2>
            <?php
            $total = wp_count_posts('kavram')->publish;
            $empty = get_posts(array(
                'post_type' => 'kavram',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => '_kavram_short_def',
                        'value' => 'Açıklama eklenecek',
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_kavram_short_def',
                        'compare' => 'NOT EXISTS'
                    )
                ),
                'fields' => 'ids'
            ));
            $empty_count = count($empty);
            ?>
            
            <p>📊 Toplam Kavram: <strong><?php echo $total; ?></strong></p>
            <p>✅ Açıklaması Var: <strong><?php echo $total - $empty_count; ?></strong></p>
            <p>⏳ Açıklama Bekleyen: <strong><?php echo $empty_count; ?></strong></p>
            
            <?php if ($empty_count > 0): ?>
                <hr>
                <form method="post">
                    <?php wp_nonce_field('generate_descriptions'); ?>
                    <p>
                        <label>Her seferde işlenecek kavram sayısı:</label><br>
                        <select name="batch_size">
                            <option value="5">5 kavram</option>
                            <option value="10" selected>10 kavram</option>
                            <option value="25">25 kavram</option>
                            <option value="50">50 kavram</option>
                        </select>
                    </p>
                    <p>
                        <button type="submit" name="start_generation" class="button button-primary button-large">
                            🚀 Açıklama Oluştur (Gemini)
                        </button>
                    </p>
                    <p style="color: green;"><strong>✓ Gemini API ücretsiz!</strong></p>
                </form>
            <?php else: ?>
                <p style="color: green; font-weight: bold;">✅ Tüm kavramların açıklaması mevcut!</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

function nedir_process_batch_gemini() {
    $batch_size = intval($_POST['batch_size'] ?? 10);
    $api_key = GEMINI_API_KEY;
    
    $kavramlar = get_posts(array(
        'post_type' => 'kavram',
        'posts_per_page' => $batch_size,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_kavram_short_def',
                'value' => 'Açıklama eklenecek',
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_kavram_short_def',
                'compare' => 'NOT EXISTS'
            )
        )
    ));
    
    if (empty($kavramlar)) {
        echo '<div class="notice notice-success"><p>✅ İşlenecek kavram kalmadı!</p></div>';
        return;
    }
    
    $success = 0;
    $errors = 0;
    
    echo '<div class="notice notice-info"><p>🔄 İşleniyor...</p></div>';
    echo '<ul>';
    
    foreach ($kavramlar as $kavram) {
        $title = $kavram->post_title;
        
        $terms = get_the_terms($kavram->ID, 'ana-kategori');
        $category = $terms ? $terms[0]->name : 'Genel';
        
        echo "<li><strong>{$title}</strong> ({$category}): ";
        
        $result = nedir_call_gemini($title, $category, $api_key);
        
        if ($result && !isset($result['error'])) {
            update_post_meta($kavram->ID, '_kavram_short_def', sanitize_text_field($result['short_def']));
            update_post_meta($kavram->ID, '_kavram_example', sanitize_textarea_field($result['example']));
            
            wp_update_post(array(
                'ID' => $kavram->ID,
                'post_content' => wp_kses_post($result['content'])
            ));
            
            echo '<span style="color: green;">✅ Başarılı</span></li>';
            $success++;
        } else {
            $error_msg = $result['error'] ?? 'Bilinmeyen hata';
            echo '<span style="color: red;">❌ Hata: ' . esc_html($error_msg) . '</span></li>';
            $errors++;
        }
        
        usleep(500000); // Rate limiting
    }
    
    echo '</ul>';
    echo '<div class="notice notice-success"><p>✅ Başarılı: ' . $success . ' | ❌ Hata: ' . $errors . '</p></div>';
    echo '<p><a href="" class="button">🔄 Devam Et</a></p>';
}

function nedir_call_gemini($title, $category, $api_key) {
    $prompt = "Sen Nedir.me için içerik yazarısın. \"{$title}\" kavramı için Türkçe açıklama yaz.

Kategori: {$category}

Format:
1. KISA TANIM (1 cümle, max 20 kelime)
2. AÇIKLAMA (2-3 paragraf, sade ve anlaşılır dil, HTML <p> tagları ile)
3. GÜNLÜK HAYAT ÖRNEĞİ (1 paragraf)

Kurallar:
- Sade, anlaşılır Türkçe kullan
- Herkesin anlayabileceği şekilde yaz
- Teknik terimleri açıkla
- Emoji kullanma

Cevabını SADECE JSON formatında ver, başka hiçbir şey yazma:
{
  \"short_def\": \"Kısa tanım buraya\",
  \"content\": \"<p>Ana açıklama paragraf 1</p><p>Paragraf 2</p>\",
  \"example\": \"Günlük hayat örneği buraya\"
}";

    $data = array(
        'contents' => array(
            array(
                'parts' => array(
                    array('text' => $prompt)
                )
            )
        ),
        'generationConfig' => array(
            'temperature' => 0.7,
            'maxOutputTokens' => 1000,
        )
    );

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $api_key;
    
    $response = wp_remote_post($url, array(
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($data),
        'timeout' => 30,
    ));

    if (is_wp_error($response)) {
        return array('error' => $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (isset($result['error'])) {
        return array('error' => $result['error']['message']);
    }

    $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
    
    // JSON parse
    preg_match('/\{[\s\S]*\}/', $content, $matches);
    if (!empty($matches[0])) {
        $parsed = json_decode($matches[0], true);
        if ($parsed) {
            return $parsed;
        }
    }

    return array('error' => 'JSON parse hatası');
}
