<?php
class VnAIContentAdmin
{
    private $options;
    function __construct()
    {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'init_page'));
    }
    public function create_page()
    {
        $this->options = get_option('vnaicontent_option');
        require_once VNAICONTENT_PATH . 'layout/common.php';
    }


    public function add_page()
    {
        add_options_page(
            'Settings Admin',
            'VnAIContent',
            'manage_options',
            'vnaicontent',
            array($this, 'create_page')
        );
    }
    public function sanitize($input)
    {
        $new_input = array();

        if (isset($input['user_key'])) {
            $new_input['user_key'] = sanitize_text_field($input['user_key']);
        }
        if (isset($input['lang'])) {
            $new_input['lang'] = sanitize_text_field($input['lang']);
        } else {
            $new_input['lang'] = 'Việt';
        }
        if (isset($input['type_ai'])) {
            $new_input['type_ai'] = sanitize_text_field($input['type_ai']);
        } else {
            $new_input['type_ai'] = 'gemini';
        }
        //cron
        $img_action_time = !empty($input['img_action_time']) ? $input['img_action_time'] : 0;
        $post_action_time = !empty($input['post_action_time']) ? $input['post_action_time'] : 0;
        $publish_action_time = !empty($input['publish_action_time']) ? $input['publish_action_time'] : 0;
        $audio_action_time = !empty($input['audio_action_time']) ? $input['audio_action_time'] : 0;
        $yt_action_time = !empty($input['yt_action_time']) ? $input['yt_action_time'] : 0;
        $tag_action_time = !empty($input['tag_action_time']) ? $input['tag_action_time'] : 0;
        $des_action_time = !empty($input['des_action_time']) ? $input['des_action_time'] : 0;
        $gbp_action_time = !empty($input['gbp_action_time']) ? $input['gbp_action_time'] : 0;
        $feed_podcast_action_time = !empty($input['feed_podcast_action_time']) ? $input['feed_podcast_action_time'] : 0;
        if ($img_action_time > 0) {
            $post_action_time = 0;
        }
        $new_input['post_action_time'] = $post_action_time;
        $new_input['img_action_time'] = $img_action_time;
        $new_input['publish_action_time'] = $publish_action_time;
        $new_input['audio_action_time'] = $audio_action_time;
        $new_input['yt_action_time'] = $yt_action_time;
        $new_input['tag_action_time'] = $tag_action_time;
        $new_input['des_action_time'] = $des_action_time;
        $new_input['gbp_action_time'] = $gbp_action_time;
        $new_input['feed_podcast_action_time'] = $feed_podcast_action_time;

        if (isset($input['loop_run'])) {
            $new_input['loop_run'] = sanitize_text_field($input['loop_run']);
        }
        //remove
        if (isset($input['min_word'])) {
            $new_input['min_word'] = sanitize_text_field($input['min_word']);
        }
        if (isset($input['not_img'])) {
            $new_input['not_img'] = sanitize_text_field($input['not_img']);
        }
        //re_run
        if (isset($input['re_run_keyword_miss'])) {
            $new_input['re_run_keyword_miss'] = sanitize_text_field($input['re_run_keyword_miss']);
        }
        if (isset($input['re_run_keyword_remove'])) {
            $new_input['re_run_keyword_remove'] = sanitize_text_field($input['re_run_keyword_remove']);
        }

        if (isset($input['user'])) {
            $user = sanitize_text_field($input['user']);
            if ($user == '') {
                $user = 1;
            }
            $new_input['user'] = $user;
        }
        if (isset($input['draft'])) {
            $new_input['draft'] = sanitize_text_field($input['draft']);
        }
        if (isset($input['link_cur'])) {
            $new_input['link_cur'] = sanitize_text_field($input['link_cur']);
        }
        if (isset($input['link_brand'])) {
            $new_input['link_brand'] = sanitize_text_field($input['link_brand']);
        }
        if (isset($input['log'])) {
            $new_input['log'] = sanitize_text_field($input['log']);
        }
        //prompts
        if (isset($input['ai_as'])) {
            $new_input['ai_as'] = sanitize_textarea_field($input['ai_as']);
        }
        if (isset($input['ai_as_cate'])) {
            $ai_as_cate = $input['ai_as_cate'];
            if (!empty($ai_as_cate)) {
                foreach ($ai_as_cate as $key => $value) {
                    update_term_meta($key, 'ai_as', sanitize_textarea_field(htmlspecialchars($value, ENT_QUOTES)));
                }
            }
        }

        if (isset($input['prompt'])) {
            $new_input['prompt'] = sanitize_textarea_field($input['prompt']);
        }
        if (isset($input['prompt_cate'])) {
            $prompt_cate = $input['prompt_cate'];
            if (!empty($prompt_cate)) {
                foreach ($prompt_cate as $key => $value) {
                    update_term_meta($key, 'prompt', sanitize_textarea_field(htmlspecialchars($value, ENT_QUOTES)));
                }
            }
        }
        //gemini
        if (isset($input['gemini_api_key'])) {
            $new_input['gemini_api_key'] = sanitize_textarea_field($input['gemini_api_key']);
        }
        if (isset($input['gemini_model'])) {
            $new_input['gemini_model'] = sanitize_text_field($input['gemini_model']);
        }
        if (isset($input['gemini_proxy'])) {
            $new_input['gemini_proxy'] = sanitize_text_field($input['gemini_proxy']);
        }
        //openai
        if (isset($input['openai_api_key'])) {
            $new_input['openai_api_key'] = sanitize_textarea_field($input['openai_api_key']);
        }
        if (isset($input['openai_model'])) {
            $new_input['openai_model'] = sanitize_text_field($input['openai_model']);
        } else {
            $new_input['openai_model'] = 'gpt-4o';
        }
        $openai_endpoint = !empty($input['openai_endpoint']) ? $input['openai_endpoint'] : 'https://api.openai.com/v1/chat/completions';
        $new_input['openai_endpoint'] = sanitize_text_field($openai_endpoint);
        //claude
        if (isset($input['claude_api_key'])) {
            $new_input['claude_api_key'] = sanitize_textarea_field($input['claude_api_key']);
        }
        if (isset($input['claude_model'])) {
            $new_input['claude_model'] = sanitize_text_field($input['claude_model']);
        } else {
            $new_input['claude_model'] = 'claude-3-5-sonnet-20240620';
        }
        $claude_endpoint = !empty($input['claude_endpoint']) ? $input['claude_endpoint'] : 'https://api.anthropic.com/v1/messages';
        $new_input['claude_endpoint'] = sanitize_text_field($claude_endpoint);
        //azure
        if (isset($input['azure_openai_key'])) {
            $new_input['azure_openai_key'] = sanitize_text_field($input['azure_openai_key']);
        }
        if (isset($input['azure_openai_text_endpoint'])) {
            $new_input['azure_openai_text_endpoint'] = sanitize_text_field($input['azure_openai_text_endpoint']);
        }
        //audio
        if (isset($input['audio_type'])) {
            $new_input['audio_type'] = sanitize_text_field($input['audio_type']);
        }
        if (isset($input['audio_status_post'])) {
            $new_input['audio_status_post'] = sanitize_text_field($input['audio_status_post']);
        }
        if (isset($input['audio_check_thumb'])) {
            $new_input['audio_check_thumb'] = sanitize_text_field($input['audio_check_thumb']);
        }
        if (isset($input['audio_exclude_cat'])) {
            $audio_exclude_cat = !empty($input['audio_exclude_cat']) ? implode(',', $input['audio_exclude_cat']) : '';
            $new_input['audio_exclude_cat'] = $audio_exclude_cat;
        }
        if (isset($input['audio_player'])) {
            $new_input['audio_player'] = sanitize_text_field($input['audio_player']);
        }
        if (isset($input['audio_show'])) {
            $new_input['audio_show'] = sanitize_text_field($input['audio_show']);
        }
        //yt
        if (isset($input['yt_show'])) {
            $new_input['yt_show'] = sanitize_text_field($input['yt_show']);
        }
        if (isset($input['yt_url'])) {
            $new_input['yt_url'] = sanitize_text_field($input['yt_url']);
        }
        if (isset($input['podcast_thumb'])) {
            $new_input['podcast_thumb'] = sanitize_text_field($input['podcast_thumb']);
        }
        if (isset($input['audio_use_prompt'])) {
            $new_input['audio_use_prompt'] = sanitize_text_field($input['audio_use_prompt']);
        }
        if (isset($input['audio_prompt'])) {
            $new_input['audio_prompt'] = sanitize_textarea_field($input['audio_prompt']);
        }
        if (isset($input['audio_voice_viettel'])) {
            $new_input['audio_voice_viettel'] = sanitize_text_field($input['audio_voice_viettel']);
        }
        if (isset($input['audio_voice_viettel2'])) {
            $new_input['audio_voice_viettel2'] = sanitize_text_field($input['audio_voice_viettel2']);
        }
        if (isset($input['viettel_proxy'])) {
            $new_input['viettel_proxy'] = sanitize_text_field($input['viettel_proxy']);
        }
        if (isset($input['viettel_token'])) {
            $new_input['viettel_token'] = sanitize_text_field($input['viettel_token']);
        }
        if (isset($input['audio_fpt_api'])) {
            $new_input['audio_fpt_api'] = sanitize_textarea_field($input['audio_fpt_api']);
        }
        if (isset($input['audio_voice_fpt'])) {
            $new_input['audio_voice_fpt'] = sanitize_text_field($input['audio_voice_fpt']);
        }
        if (isset($input['audio_voice_fpt2'])) {
            $new_input['audio_voice_fpt2'] = sanitize_text_field($input['audio_voice_fpt2']);
        }
        if (isset($input['audio_zalo_api'])) {
            $new_input['audio_zalo_api'] = sanitize_textarea_field($input['audio_zalo_api']);
        }
        if (isset($input['audio_voice_zalo'])) {
            $new_input['audio_voice_zalo'] = sanitize_text_field($input['audio_voice_zalo']);
        }
        if (isset($input['audio_voice_zalo2'])) {
            $new_input['audio_voice_zalo2'] = sanitize_text_field($input['audio_voice_zalo2']);
        }
        if (isset($input['audio_gg_api'])) {
            $new_input['audio_gg_api'] = sanitize_text_field($input['audio_gg_api']);
        }
        if (isset($input['audio_lang_gg'])) {
            $new_input['audio_lang_gg'] = sanitize_text_field($input['audio_lang_gg']);
        }
        if (isset($input['audio_voice_gg'])) {
            $new_input['audio_voice_gg'] = sanitize_text_field($input['audio_voice_gg']);
        }
        if (isset($input['audio_voice_gg2'])) {
            $new_input['audio_voice_gg2'] = sanitize_text_field($input['audio_voice_gg2']);
        }
        if (isset($input['audio_openai_api'])) {
            $new_input['audio_openai_api'] = sanitize_text_field($input['audio_openai_api']);
        }
        if (isset($input['audio_openai_model'])) {
            $new_input['audio_openai_model'] = sanitize_text_field($input['audio_openai_model']);
        }
        if (isset($input['audio_openai_voice'])) {
            $new_input['audio_openai_voice'] = sanitize_text_field($input['audio_openai_voice']);
        }
        if (isset($input['audio_openai_voice2'])) {
            $new_input['audio_openai_voice2'] = sanitize_text_field($input['audio_openai_voice2']);
        }
        $new_input['audio_openai_endpoint'] = !empty($input['audio_openai_endpoint']) ? sanitize_text_field($input['audio_openai_endpoint']) : 'https://api.openai.com/v1/audio/speech';
        if (isset($input['audio_azure_speech_region'])) {
            $new_input['audio_azure_speech_region'] = sanitize_text_field($input['audio_azure_speech_region']);
        }
        if (isset($input['audio_azure_speech_api'])) {
            $new_input['audio_azure_speech_api'] = sanitize_text_field($input['audio_azure_speech_api']);
        }
        if (isset($input['audio_lang_azure_speech'])) {
            $new_input['audio_lang_azure_speech'] = sanitize_text_field($input['audio_lang_azure_speech']);
        }
        if (isset($input['azure_speech_gender'])) {
            $new_input['azure_speech_gender'] = sanitize_text_field($input['azure_speech_gender']);
        }
        if (isset($input['azure_speech_gender2'])) {
            $new_input['azure_speech_gender2'] = sanitize_text_field($input['azure_speech_gender2']);
        }
        if (isset($input['audio_voice_azure_speech'])) {
            $new_input['audio_voice_azure_speech'] = sanitize_text_field($input['audio_voice_azure_speech']);
        }
        if (isset($input['audio_voice_azure_speech2'])) {
            $new_input['audio_voice_azure_speech2'] = sanitize_text_field($input['audio_voice_azure_speech2']);
        }
        if (isset($input['audio_azure_openai_key'])) {
            $new_input['audio_azure_openai_key'] = sanitize_text_field($input['audio_azure_openai_key']);
        }
        if (isset($input['audio_azure_openai_endpoint'])) {
            $new_input['audio_azure_openai_endpoint'] = sanitize_text_field($input['audio_azure_openai_endpoint']);
        }
        if (isset($input['audio_voice_azure_openai'])) {
            $new_input['audio_voice_azure_openai'] = sanitize_text_field($input['audio_voice_azure_openai']);
        }
        if (isset($input['audio_voice_azure_openai2'])) {
            $new_input['audio_voice_azure_openai2'] = sanitize_text_field($input['audio_voice_azure_openai2']);
        }
        if (isset($input['audio_voice_vbee'])) {
            $new_input['audio_voice_vbee'] = sanitize_text_field($input['audio_voice_vbee']);
        }
        if (isset($input['audio_voice_vbee2'])) {
            $new_input['audio_voice_vbee2'] = sanitize_text_field($input['audio_voice_vbee2']);
        }
        if (isset($input['audio_vbee_token'])) {
            $new_input['audio_vbee_token'] = $input['audio_vbee_token'];
        }
        //img
        if (isset($input['img_by'])) {
            $new_input['img_by'] = sanitize_text_field($input['img_by']);
        }
        if (isset($input['num_img'])) {
            $new_input['num_img'] = sanitize_text_field($input['num_img']);
        }
        if (isset($input['format_img'])) {
            $new_input['format_img'] = sanitize_text_field($input['format_img']);
        }
        if (isset($input['resize_img'])) {
            $new_input['resize_img'] = sanitize_text_field($input['resize_img']);
        }
        if (isset($input['status_post_img'])) {
            $new_input['status_post_img'] = sanitize_text_field($input['status_post_img']);
        }
        if (isset($input['only_img_ai'])) {
            $new_input['only_img_ai'] = sanitize_text_field($input['only_img_ai']);
        }
        if (isset($input['exclude_cat_img'])) {
            $exclude_cat_img = !empty($input['exclude_cat_img']) ? implode(',', $input['exclude_cat_img']) : '';
            $new_input['exclude_cat_img'] = $exclude_cat_img;
        }
        //dall e
        if (isset($input['openai_model_img'])) {
            $new_input['openai_model_img'] = sanitize_text_field($input['openai_model_img']);
        }
        if (isset($input['openai_size_img'])) {
            $new_input['openai_size_img'] = sanitize_text_field($input['openai_size_img']);
        }
        if (isset($input['openai_api_key_img'])) {
            $new_input['openai_api_key_img'] = sanitize_textarea_field($input['openai_api_key_img']);
        }
        $openai_img_endpoint = !empty($input['openai_img_endpoint']) ? $input['openai_img_endpoint'] : 'https://api.openai.com/v1/images/generations';
        $new_input['openai_img_endpoint'] = sanitize_text_field($openai_img_endpoint);
        //cloudflare
        if (isset($input['cloudflare_model_img'])) {
            $new_input['cloudflare_model_img'] = sanitize_text_field($input['cloudflare_model_img']);
        }
        if (isset($input['cf_acc_token'])) {
            $new_input['cf_acc_token'] = sanitize_textarea_field($input['cf_acc_token']);
        }
        //huggingface
        if (isset($input['huggingface_model_img'])) {
            $new_input['huggingface_model_img'] = sanitize_text_field($input['huggingface_model_img']);
        }
        if (isset($input['huggingface_token'])) {
            $new_input['huggingface_token'] = sanitize_textarea_field($input['huggingface_token']);
        }
        //gg seach
        if (isset($input['gg_search_api_img'])) {
            $new_input['gg_search_api_img'] = sanitize_textarea_field($input['gg_search_api_img']);
        }
        if (isset($input['domain_img'])) {
            $new_input['domain_img'] = sanitize_text_field($input['domain_img']);
        }
        if (isset($input['gg_search_img_country'])) {
            $new_input['gg_search_img_country'] = sanitize_text_field($input['gg_search_img_country']);
        }
        if (isset($input['gg_search_img_lang'])) {
            $new_input['gg_search_img_lang'] = sanitize_text_field($input['gg_search_img_lang']);
        }
        if (isset($input['gg_search_img_country_publish'])) {
            $new_input['gg_search_img_country_publish'] = sanitize_text_field($input['gg_search_img_country_publish']);
        }
        //abacus
        if (isset($input['abacus_project_id'])) {
            $new_input['abacus_project_id'] = sanitize_text_field($input['abacus_project_id']);
        }
        if (isset($input['abacus_api'])) {
            $new_input['abacus_api'] = sanitize_text_field($input['abacus_api']);
        }
        if (isset($input['abacus_token'])) {
            $new_input['abacus_token'] = sanitize_text_field($input['abacus_token']);
        }
        if (isset($input['abacus_models_json'])) {
            $new_input['abacus_models_json'] = sanitize_text_field($input['abacus_models_json']);
        }
        if (isset($input['abacus_model'])) {
            $new_input['abacus_model'] = sanitize_text_field($input['abacus_model']);
        }
        //Related posts
        if (isset($input['text_more'])) {
            $new_input['text_more'] = sanitize_text_field($input['text_more']);
        }
        if (isset($input['num_relatedposts'])) {
            $new_input['num_relatedposts'] = sanitize_text_field($input['num_relatedposts']);
        }
        //toc
        if (isset($input['toc'])) {
            $new_input['toc'] = sanitize_text_field($input['toc']);
        }
        if (isset($input['toc_text'])) {
            $new_input['toc_text'] = sanitize_text_field($input['toc_text']);
        }
        //tag
        if (isset($input['cat_tag'])) {
            $new_input['cat_tag'] = sanitize_text_field($input['cat_tag']);
        }
        if (isset($input['open_tab'])) {
            $new_input['open_tab'] = sanitize_text_field($input['open_tab']);
        }
        //create tag
        if (isset($input['num_tag'])) {
            $new_input['num_tag'] = sanitize_text_field($input['num_tag']);
        }
        if (isset($input['exclude_tag'])) {
            $new_input['exclude_tag'] = sanitize_textarea_field($input['exclude_tag']);
        }
        //feed podcast
        if (isset($input['feed_podcast_order'])) {
            $new_input['feed_podcast_order'] = sanitize_textarea_field($input['feed_podcast_order']);
        }
        if (isset($input['feed_podcast_offset'])) {
            $new_input['feed_podcast_offset'] = sanitize_textarea_field($input['feed_podcast_offset']);
        }
        return $new_input;
    }
    public function init_page()
    {
        register_setting(
            'vnaicontent_option_group',
            'vnaicontent_option',
            array($this, 'sanitize')
        );
        add_settings_section(
            'section_id',
            '',
            array($this, 'section_info'),
            'vnaicontent'
        );
    }
    public function create_callback($args) {}
    public function section_info() {}
}
if (is_admin()) {
    new VnAIContentAdmin();
}
