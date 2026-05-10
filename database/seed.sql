-- ============================================================
-- KompMind — Boshlang'ich ma'lumotlar
-- ============================================================

USE kfkaiedu_db;

-- ------------------------------------------------------------
-- MODULLAR (8 ta)
-- ------------------------------------------------------------
INSERT INTO modules (slug, name, icon, description, bloom_level, sort_order, badge) VALUES
('analitik-fikrlash',    'Analitik fikrlash',      '🔍', 'Platformalar va kurslarni chuqur tahlil qilish ko''nikmasi',  4, 1, 'new'),
('taqqoslash',           'Taqqoslash ko''nikmasi',   '⚖️', 'Bir nechta variantni mezonlar bo''yicha solishtirish',        2, 2, ''),
('muammo-hal',           'Muammolarni hal qilish',  '🧠', 'Muammolarga texnik, pedagogik va kreativ yechimlar topish',  3, 3, 'hot'),
('sistematik',           'Sistematik yondashuv',    '📊', 'Kurs va mavzularni bosqichlarga ajratib, xaritalash',        3, 4, ''),
('kritik-fikrlash',      'Kritik fikrlash',         '🧩', 'Kuchli va zaif tomonlarni aniqlash, tanqidiy baho berish',   5, 5, ''),
('amalda-qollash',       'Amalda qo''llash',         '🛠', 'Bilimni real vaziyatda qo''llash va mini dars yaratish',      3, 6, ''),
('yaratuvchanlik',       'Yaratuvchanlik',           '🎨', 'Bir mavzuni turli formatlarda ifodalash va ijodiy fikrlash', 6, 7, ''),
('qaror-qabul',          'Qaror qabul qilish',      '🎯', 'Variantlarni baholash va eng optimalini tanlash',            5, 8, '');

-- ------------------------------------------------------------
-- AMALIY MASHQLAR (16 ta — har modulga 2 tadan)
-- ------------------------------------------------------------
INSERT INTO exercises (module_id, title, description, instructions, type, max_score, sort_order) VALUES

-- Modul 1: Analitik fikrlash
(1, 'Platformani parchalab tahlil qil',
 '1 ta kurs platformasini 5 qismga bo''lib, har birini baholang',
 '1. Istalgan kurs platformasini tanlang (Udemy, Coursera, Khan Academy va h.k.)\n2. Uni quyidagi 5 qismga ajrating: dizayn, kontent, test tizimi, aloqa vositalari, qulay foydalanish\n3. Har bir qismga 1-10 ball bering va sababini yozing\n4. Oxirida umumiy xulosa chiqaring va qaysi qism eng kuchli/zaifligini asoslang',
 'practical', 100, 1),

(1, 'Nima ishlayapti / nima ishlamayapti',
 '2 ta kursni ko''rib, ularning kuchli va zaif tomonlarini aniqlang',
 '1. Istalgan 2 ta online kursni tanlang\n2. Har biri uchun "Nima ishlayapti" va "Nima ishlamayapti" jadvalini tuzing\n3. Kamida 3-3 ta nuqta yozing\n4. Ushbu tahlil asosida ideal kurs qanday bo''lishi haqida xulosa yozing',
 'practical', 100, 2),

-- Modul 2: Taqqoslash
(2, '3x3 taqqoslash jadvali',
 '3 ta platformani 3 ta mezon bo''yicha taqqoslab, eng yaxshisini aniqlang',
 '1. 3 ta kurs platformasini tanlang\n2. 3 ta muhim mezon belgilang (narx, kontent sifati, sertifikat va h.k.)\n3. Har bir platforma uchun har bir mezon bo''yicha 1-10 ball bering\n4. Jadvalni to''ldiring va umumiy ballga ko''ra eng yaxshisini aniqlang\n5. Nima uchun aynan shu platforma yaxshi ekanligini asoslang',
 'practical', 100, 1),

(2, 'Ideal kurs vs oddiy kurs',
 'Ideal kurs va oddiy kurs o''rtasidagi farqlarni yozing',
 '1. O''zingiz ko''rgan yoki bilgan 1 ta "oddiy" kursni tanlang\n2. Ideal kurs qanday bo''lishi kerakligini tasavvur qiling\n3. Kamida 5 ta asosiy farqni jadval shaklida yozing\n4. Har bir farq uchun real misol keltiring',
 'practical', 100, 2),

-- Modul 3: Muammolarni hal qilish
(3, 'Muammo → 3 yechim',
 'Talabalar kursni oxirigacha ko''rmayapti — 3 xil yechim taklif qiling',
 'Vaziyat: Online platformadagi talabalar kursning faqat 30% ini ko''rib, qolganini tashlab ketmoqda.\n1. Texnik yechim taklif qiling (texnologiya yordamida)\n2. Pedagogik yechim taklif qiling (o''qitish metodini o''zgartirib)\n3. Kreativ yechim taklif qiling (noodatiy, innovatsion usul)\n4. Har bir yechimning afzalligi va kamchiligini yozing',
 'practical', 100, 1),

(3, 'Internetsiz o''qitish',
 '"Agar internet bo''lmasa, qanday o''qitardingiz?" degan savolga yechim yozing',
 '1. O''zingizning faniz uchun internet bo''lmagan sharoitda o''qitish rejasini tuzing\n2. Qanday resurslardan foydalanasiz? (daftarlar, flashcard, guruh ishlari...)\n3. O''quvchilar bilimini qanday baholaysiz?\n4. Bu usulning onlayn o''qitishdan farqini yozing',
 'practical', 100, 2),

-- Modul 4: Sistematik yondashuv
(4, 'Kurs xaritasi',
 '1 mavzuni bosqichlarga bo''lib, diagramma shaklida chizing',
 '1. O''zingizning fanizdan 1 ta murakkab mavzuni tanlang\n2. Uni 3 bosqichga ajrating: kirish, asosiy qism, mustahkamlash\n3. Har bosqich uchun nimalar o''rgatilishi kerakligini yozing\n4. Qanday ketma-ketlik eng samarali ekanligini izohlang\n5. Diagramma yoki chizma sifatida tasvirlang (matn ko''rinishida ham bo''ladi)',
 'practical', 100, 1),

(4, 'Ideal dars algoritmi',
 '"Ideal dars algoritmi"ni step-by-step yozing',
 '1. Sizning fikringizcha ideal dars qanday o''tkazilishi kerak?\n2. Har bir bosqichni aniq vaqt bilan belgilang (masalan: kirish — 5 daqiqa)\n3. O''qituvchi va talabaning har bosqichdagi roli qanday?\n4. Har bosqichda qanday metodlar ishlatiladi?\n5. Umumiy algoritmni raqamlangan ro''yxat sifatida yozing',
 'practical', 100, 2),

-- Modul 5: Kritik fikrlash
(5, 'Tanqidiy review',
 '1 ta kursni tanlab, kuchli va zaif tomonlarini yozing',
 '1. O''zingiz ko''rgan yoki bilgan 1 ta kursni tanlang\n2. 3 ta kuchli tomonini konkret misollar bilan yozing\n3. 3 ta zaif tomonini konkret misollar bilan yozing\n4. Kursni qanday yaxshilash mumkinligi bo''yicha 3 ta tavsiya bering\n5. Umumiy baho: 1-10 ball va asoslash',
 'practical', 100, 1),

(5, 'Bu kurs foydali?',
 '"Bu kurs haqiqatan ham foydalimi?" savoliga dalillar bilan javob bering',
 '1. Istalgan kursni tanlang\n2. Kurs foydali ekanligini isbotlovchi 3 ta dalil keltiring\n3. Kurs foydasiz ekanligini isbotlovchi 3 ta qarama-qarshi dalil keltiring\n4. Ikkala tomonning dalillarini taroziga solib, xulosa yozing\n5. Kursni kim uchun tavsiya qilasiz, kim uchun tavsiya qilmaysiz?',
 'practical', 100, 2),

-- Modul 6: Amalda qo'llash
(6, 'Mini dars yarat',
 '1 mavzu bo''yicha 5 slaydli dars va 3 ta test savoli yarating',
 '1. O''zingizning fanizdan 1 ta mavzuni tanlang\n2. 5 ta slaydning mazmunini yozing:\n   - Slayd 1: Sarlavha va maqsad\n   - Slayd 2-3: Asosiy ma''lumot\n   - Slayd 4: Misol\n   - Slayd 5: Xulosa\n3. 3 ta test savoli tuzing (har biri 4 ta variant bilan)\n4. To''g''ri javoblarni belgilang',
 'practical', 100, 1),

(6, 'Real muammoni hal qil',
 'O''rgangan bilim asosida real muammoni hal qiling',
 '1. O''zingiz duch kelgan real muammoni (ta''lim, texnologiya, kundalik hayot) tanlang\n2. Kursda o''rgangan qaysi bilim/ko''nikma bu muammoni hal qilishga yordam beradi?\n3. Yechimni bosqichma-bosqich tavsiflab bering\n4. Natijani qanday o''lchasiz?\n5. Bu tajribadan nima o''rgandingiz?',
 'practical', 100, 2),

-- Modul 7: Yaratuvchanlik
(7, '1 mavzu – 3 format',
 '1 mavzuni rasm, hikoya va diagramma formatida tushuntiring',
 '1. O''zingizning fanizdan 1 ta tushunchani tanlang\n2. Uni uchta har xil formatda tushuntiring:\n   - Rasm shaklida: tasvirni so''zlar bilan ifodalang (yoki chizing)\n   - Hikoya shaklida: qisqa hikoya orqali tushuntiring\n   - Diagramma shaklida: sxema, jadval yoki grafik sifatida tasvirlang\n3. Qaysi format eng samarali bo''ldi? Nima uchun?',
 'practical', 100, 1),

(7, 'Bolaga tushuntir',
 'Murakkab tushunchani bolaga tushuntirgandek yozing',
 '1. O''z fanizdan eng murakkab tushunchani tanlang\n2. Uni 7-8 yoshli bolaga tushuntirgandek, oddiy til bilan yozing\n3. Qanday o''xshatmalar va misollar ishlatdingiz?\n4. Yangi til bilan tushuntirishda siz ham yangi narsani angladingizmi?',
 'practical', 100, 2),

-- Modul 8: Qaror qabul qilish
(8, 'Eng yaxshisini tanla',
 '3 ta kurs platformasiga ball berib, eng optimalini tanlang',
 '1. Sizga 3 ta variant taqdim etiladi: A) Bepul, kontent ko''p, sertifikat yo''q; B) Oyiga $10, o''rta kontent, sertifikat bor; C) Oyiga $50, premium kontent, mentorlik bor\n2. Har bir variantga quyidagi mezonlar bo''yicha ball bering: narx, sifat, qulaylik, karyera uchun foyda\n3. Balllarni hisoblang va eng optimalini tanlang\n4. Sabab va asoslaringizni yozing',
 'practical', 100, 1),

(8, 'Qaysi platformani tanlardingiz?',
 '"Agar siz o''qituvchi bo''lsangiz, qaysi platformani tanlardingiz?" savoliga javob bering',
 '1. O''qituvchi sifatida kurs joylashtirish uchun 3 ta platformani ko''rib chiqing\n2. Har birini o''qituvchi nuqtai nazaridan baholang: narx, auditoriya, qo''llab-quvvatlash, imkoniyatlar\n3. Nega aynan shu platformani tanladingizni asoslang\n4. Bu qarorni qabul qilishda qanday omillar hal qiluvchi bo''ldi?',
 'practical', 100, 2);

-- ------------------------------------------------------------
-- IJODIY VAZIFALAR (8 ta)
-- ------------------------------------------------------------
INSERT INTO creative_tasks (exercise_id, task_type, elements, prompt) VALUES

(1,  'elements_game',
 '["kitob", "robot", "suv", "shahar", "musobaqa", "quyosh", "kod"]',
 'Yuqoridagi elementlardan foydalanib, ta''limga oid bitta yagona hikoya yoki ijtimoiy reklama matnini yarating. Barcha elementlar hikoyada ishtirok etishi shart.'),

(3,  'broken_composition',
 NULL,
 'Quyida tartibsiz yozilgan matn beriladi. Uni mantiqiy va chiroyli kompozitsiyaga keltiring: "Xulosa chiqarish muhim. Avval maqsad qo''yiladi. Keyin tahlil qilinadi. Muammo aniqlanadi. So''ng yechim topiladi."'),

(5,  'one_word',
 '["ta''lim", "texnologiya", "ijodiyot", "kelajak", "bilim"]',
 'Tasodifiy bitta so''z asosida poster g''oyasi, hikoya yoki loyiha dizayni yarating. Rang, shakl va ramzlar haqida ham yozing.'),

(7,  'composition_builder',
 '["muammo", "jarayon", "yechim", "natija", "teskari aloqa", "takomil"]',
 'Berilgan 6 ta elementni mantiqiy ketma-ketlikda joylashtiring va har birini o''quv jarayoniga bog''lab tushuntiring. Nima uchun aynan shu tartib?'),

(9,  'ai_reviewer',
 NULL,
 'O''z tanqidiy review ishingizni yozing. Keyin "AI tekshiruvchi" roliga kiring: xatolarni toping, yaxshilash bo''yicha 3 ta aniq tavsiya bering. Ikki rol o''rtasida farq bormi?'),

(11, 'three_stage_story',
 NULL,
 'Uch bosqichli hikoya yarating: 1) Boshlanish — muammo (biror talaba qiynalyapti), 2) Rivojlanish — jarayon (u nima qildi), 3) Yakun — yechim (natija qanday bo''ldi). Har bosqich kamida 3 jumla.'),

(13, 'reverse_thinking',
 NULL,
 '"Yaxshi kurs" emas, "eng yomon kurs" dizayni yarating. Keyin uni qarama-qarshisiga aylantiring. Bu jarayon orqali nima o''rgandingiz?'),

(15, 'color_emotion',
 '{"quvonch": "sariq", "qayg''u": "ko''k", "energiya": "qizil", "tinchlik": "yashil", "ijodiyot": "binafsha"}',
 'Berilgan rang-hissiyot juftliklari asosida ta''limga oid poster yoki dizayn g''oyasini matn shaklida tasvirlang. Har rangdan kamida bittasini ishlating.');

-- ------------------------------------------------------------
-- FOYDALANUVCHILAR (parol: password)
-- bcrypt cost=12 hash
-- ------------------------------------------------------------
INSERT INTO users (name, email, password_hash, role, email_verified_at) VALUES
('Admin',            'admin@kompmind.uz',   '$2y$12$WEBxm/4O5DTrM83ieWtzhuCv0UWgJChIu4ZwbGSFFEtJpGkp79ZAe', 'admin',   NOW()),
('Shohruh Mahkamov', 'shohruh@kompmind.uz', '$2y$12$WEBxm/4O5DTrM83ieWtzhuCv0UWgJChIu4ZwbGSFFEtJpGkp79ZAe', 'teacher', NOW()),
('Test Talaba',      'talaba@kompmind.uz',  '$2y$12$WEBxm/4O5DTrM83ieWtzhuCv0UWgJChIu4ZwbGSFFEtJpGkp79ZAe', 'student', NOW());

-- ------------------------------------------------------------
-- BOSHLANG'ICH TARAQQIYOT (test talaba uchun)
-- ------------------------------------------------------------
INSERT INTO progress (user_id, module_id, total_score, max_possible, exercises_done, exercises_total, percent) VALUES
(3, 1, 85,  200, 1, 2, 43),
(3, 2, 0,   200, 0, 2, 0),
(3, 3, 100, 200, 1, 2, 50),
(3, 4, 0,   200, 0, 2, 0),
(3, 5, 0,   200, 0, 2, 0),
(3, 6, 0,   200, 0, 2, 0),
(3, 7, 0,   200, 0, 2, 0),
(3, 8, 0,   200, 0, 2, 0);
