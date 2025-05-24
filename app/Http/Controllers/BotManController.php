<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

class BotManController extends Controller
{
    public function handle()
    {
        // Load driver
        DriverManager::loadDriver(WebDriver::class);

        // Create BotMan instance
        $botman = BotManFactory::create([
            'web' => [
                'matchingData' => [
                    'driver' => 'web',
                ],
            ]
        ]);

        // Define bot conversations
        $this->defineBotConversations($botman);

        // Listen for messages
        $botman->listen();
    }

    private function defineBotConversations(BotMan $botman)
    {
        // Greeting
        $botman->hears('halo|hai|hello|hi', function (BotMan $bot) {
            $bot->reply('Halo! 👋 Saya adalah Bjorwi Chatbot yang dibuat dengan Laravel dan Botman. Bagaimana saya bisa membantu Anda hari ini?');
            $this->showQuickReplies($bot, ['Apa itu Botman?', 'Kata-kata hari ini', 'Jokes bapak-bapak', 'List prompt']);
        });

        // Apa itu Botman
        $botman->hears('apa itu botman|about botman|tentang botman|botman', function (BotMan $bot) {
            $response = '🤖 Botman adalah framework PHP yang powerful untuk membuat chatbot! ' .
                'Dibuat khusus untuk Laravel, memungkinkan Anda membuat bot yang bisa bekerja ' .
                'di berbagai platform seperti Telegram, Facebook Messenger, Slack, dan web.';
            $bot->reply($response);
            $this->showQuickReplies($bot, ['Keunggulan Botman', 'Platform yang didukung']);
        });

        // Keunggulan Botman
        $botman->hears('keunggulan|kelebihan|advantage', function (BotMan $bot) {
            $response = "🚀 Keunggulan Botman:\n\n" .
                "✅ Framework Laravel native\n" .
                "✅ Syntax yang mudah dipahami\n" .
                "✅ Dokumentasi lengkap\n" .
                "✅ Community support yang aktif\n" .
                "✅ Testing yang mudah\n" .
                "✅ Extensible dengan middleware";
            $bot->reply($response);
            $this->showQuickReplies($bot, ['Apa itu Botman?', 'Platform yang didukung', 'Jokes bapak-bapak']);
        });

        // Platform yang didukung
        $botman->hears('platform|platform yang didukung|supported platforms', function (BotMan $bot) {
            $response = "🌐 Platform yang didukung Botman:\n\n" .
                "📱 Telegram\n" .
                "💬 Facebook Messenger\n" .
                "💼 Slack\n" .
                "🌐 Web Chat\n" .
                "📧 Email\n" .
                "📞 Twilio SMS\n" .
                "🎮 Discord\n" .
                "💻 Microsoft Teams";
            $bot->reply($response);
            $this->showQuickReplies($bot, ['Keunggulan Botman', 'Apa itu Botman?', 'Jokes bapak-bapak']);
        });



        // Siapa itu Bjorwi?
        $botman->hears('bjorwi|about bjorwi|tentang bjorwi|siapa itu bjorwi', function (BotMan $bot) {
            // Balasan teks
            $response = 'Bjorwi ialah dalang dibalik chatbot ini. Ia merupakan hacker kelas kakap di dunia IT. ' .
                'Dia ialah raja Jawa yang berkuasa di dunia ini. Namun, tahta dia sudah diturunkan kepada ' .
                'putranya yang bernama Fufufafa.';
            $bot->reply($response);

            // Kirim URL gambar sebagai bagian dari text response
            $imageUrl = 'https://pbs.twimg.com/media/Fcg_J1bacAA2oAA.jpg';
            $bot->reply("Potret legendaris Bjorwi 👑\n[IMAGE]" . $imageUrl);

            // Quick replies
            $this->showQuickReplies($bot, ['Siapa yang cocok gantiin bjorwi?','Jokes bapak-bapak', 'Kata-kata hari ini']);
        });


        // Siapa yang Cocok Pengganti Bjorwi?
        $botman->hears('pengganti bjorwi|siapa yang cocok gantiin bjorwi|gantikan bjorwi', function (BotMan $bot) {
            $response = '🔥 Erwin Smith dari Attack on Titan adalah sosok yang cocok menggantikan Bjorwi. ' .
                'Dia punya visi besar, kepemimpinan kuat, dan mampu menginspirasi pasukan di tengah kekacauan.' .
                ' Shinzou Sasageyo!✊';
            $bot->reply($response);

            // Kirim URL gambar sebagai bagian dari text response
            $imageUrl = 'https://i.pinimg.com/736x/82/9e/e9/829ee956deb1e0217207f647841effe0.jpg';
            $bot->reply("Erwin Smith ✊\n[IMAGE]" . $imageUrl);

            $this->showQuickReplies($bot, ['Siapa Bjorwi?', 'List prompt', 'Jokes bapak-bapak']);
        });


        // Jokes Bapak-Bapak
        $botman->hears('jokes|jokes bapak-bapak|bapak-bapak jokes|lagi|jokes lain', function (BotMan $bot) {
            $jokes = [
                "Makanan, makanan apa yang gampang dibuat??? Jawabannya tahu EASY HAHAHA😹😹",
                "Ikan, ikan apa yang jenisnya laki-laki semua??? Jawabannya ikan MAS HAHAHA😹😹",
                "Warna apa yang ga peduli??? BIRU DON'T CARE HAHAHA😹😹 biru dongker",
                "Jauh dimata dekat dihati itu apa??? PUSER HAHAHA😹😹",
                "Olahraga apa yang gabisa dilakuin malam malam??? LARI PAGI HAHAHA😹😹",
            ];

            $response = $jokes[array_rand($jokes)];
            $bot->reply($response);
            $this->showQuickReplies($bot, ['Lagi!', 'Jokes lain', 'Kata-kata hari ini']);
        });

        // 3 Top Cewe Cantik
        $botman->hears('3 top cewe cantik|cewe tercantik|3 cewe cantik|cewe cantik', function (BotMan $bot) {
            // Array cewe cantik dengan foto
            $ceweCantik = [
                '1. Jihyo TWICE'              => 'https://i.pinimg.com/736x/51/4e/8b/514e8be5479cf57ea6677f2c347ad5af.jpg',
                '2. Nancy Jewel Momoland'     => 'https://i.pinimg.com/736x/90/cd/74/90cd74e73551e3b5b8d3d27e9091d09e.jpg',
                '3. Naisa Alifia Yuriza'      => 'https://i.pinimg.com/736x/79/6f/b9/796fb93f6159cd4ce7076653588ea5fc.jpg',
            ];

            // Kirim pesan pembuka
            $bot->reply('✨ Berikut 3 Top Cewe Cantik versi Bjorwi:');

            // Kirim setiap cewe cantik dengan fotonya
            foreach ($ceweCantik as $nama => $imageUrl) {
                $bot->reply("$nama \n[IMAGE]" . $imageUrl);
                
            }

            $this->showQuickReplies($bot, ['Kata-kata hari ini', 'Jokes bapak-bapak', 'List prompt']);
        });


        // Kata-kata hari ini
        $botman->hears('kata-kata hari ini|quote|kata-kata lain', function (BotMan $bot) {
            $quotes = [
                "Bersyukur tanpa henti, bersabar tanpa batas. Semua akan indah pada... padahal enggak",
                "Yang sudah boleh pulang",
                "Maaf belum bisa kasih coklat, tapi insyaAllah besok ngasih seperangkat alat sholat",
                "Tak apa, setidaknya sudah berusaha",
                "Salah perbaiki, gagal coba lagi, tidak bisa pelajari",
            ];

            $response = $quotes[array_rand($quotes)];
            $bot->reply("💡 *Kata-kata hari ini:*\n\n\"$response\"");
            $this->showQuickReplies($bot, ['Quote!', 'Kata-kata lain', 'Jokes bapak-bapak']);
        });

        // List Prompt - Menampilkan semua perintah yang tersedia
        $botman->hears('list prompt|list perintah|perintah|commands|help|bantuan', function (BotMan $bot) {
            $response = "📋 *Daftar Perintah Bjorwi Chatbot:*\n\n" .
                "🤖 **Tentang Bot:**\n" .
                "• Apa itu Botman?\n" .
                "• Keunggulan Botman\n" .
                "• Platform yang didukung\n\n" .

                "👑 **Tentang Bjorwi:**\n" .
                "• Siapa itu Bjorwi?\n" .
                "• Pengganti Bjorwi\n\n" .

                "😂 **Hiburan:**\n" .
                "• Jokes bapak-bapak\n" .
                "• Kata-kata hari ini\n" .
                "• 3 top cewe cantik\n\n" .

                "🔧 **Bantuan:**\n" .
                "• List prompt (perintah ini)\n\n" .

                "💡 *Tips: Ketik salah satu perintah di atas untuk memulai!*";

            $bot->reply($response);
        });

        // Default fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Maaf, saya belum mengerti pertanyaan Anda. Coba tanyakan tentang Botman, jokes bapak-bapak, atau list prompt biar jelas 🤔');
            $this->showQuickReplies($bot, ['Apa itu Botman?', 'Jokes bapak-bapak', 'List prompt']);
        });
    }

    private function showQuickReplies(BotMan $bot, array $replies)
    {
        // Note: Quick replies untuk web driver biasanya di-handle di frontend
        // Tapi kita bisa mengirim sebagai text biasa
        if (!empty($replies)) {
            $bot->reply('Tanyakan lagi: ' . implode(' | ', $replies));
        }
    }
}