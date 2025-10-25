<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel feel to tweak each of these messages here.
    |
    */

    "accepted" => "Поле :attribute має бути прийняте.",
    "accepted_if" => "Поле :attribute має бути прийняте, коли :other має значення :value.",
    "active_url" => "Поле :attribute має невірний формат URL.",
    "after" => "Поле :attribute має бути датою після :date.",
    "after_or_equal" => "Поле :attribute має бути датою, яка рівна або пізніша за :date.",
    "alpha" => "Поле :attribute може містити лише літери.",
    "alpha_dash" => "Поле :attribute може містити тільки літери, цифри, дефіси та підкреслення.",
    "alpha_num" => "Поле :attribute може містити тільки літери та цифри.",
    "array" => "Поле :attribute має бути масивом",
    "ascii" => "Поле :attribute може містити тільки однобайтні буквено-цифрові знаки та символи.",
    "before" => "Поле :attribute повинно бути датою до :date.",
    "before_or_equal" => "Поле :attribute повинно бути датою до або рівною :date.",
    "between" => [
        "array" => "Поле :attribute має містити елементи від :min до :max.",
        "file" => "Розмір поля :attribute має бути від :min до :max кілобайт.",
        "numeric" => "Поле :attribute має бути між :min та :max ",
        "string" => "Поле :attribute має бути між символами від :min до :max.",
    ],
    "boolean" => "Поле :attribute має бути значенням true або false.",
    "confirmed" => "Підтвердження поля :attribute не співпадає.",
    "current_password" => "Пароль неправильний.",
    "date" => "Поле :attribute має некоректне значення дати.",
    "date_equals" => "Поле :attribute має бути рівним даті :date.",
    "date_format" => "Поле :attribute не відповідає формату :format.",
    "decimal" => "Поле :attribute повинно мати :decimal десяткових знаків.",
    "declined" => "Поле :attribute має бути відхилено.",
    "declined_if" => "Поле :attribute має бути відхилено, коли поле :other має значення :value.",
    "different" => "Поля :attribute та :other повинні бути різними.",
    "digits" => "Поле :attribute має бути довжиною в :digits цифр(и).",
    "digits_between" => "Поле :attribute має містити від :min до :max цифр(и).",
    "dimensions" => "Розміри зображення в полі :attribute є недійсними.",
    "distinct" => "Поле :attribute містить значення яке вже існує.",
    "doesnt_end_with" => "Поле :attribute не може закінчуватись одним з наступних значень: :values.",
    "doesnt_start_with" => "Поле :attribute не може починатись з одного з наступних значень: :values.",
    "email" => "Поле :attribute має містити дійсну адресу електронної пошти.",
    "ends_with" => "Поле :attribute повинно закінчуватись одним з наступних значень: :values.",
    "enum" => "Вибране значення для :attribute недійсне.",
    "exists" => "Вибране значення для :attribute недійсне.",
    "file" => "Поле :attribute має бути файлом.",
    "filled" => "Поле :attribute повинно мати значення.",
    "gt" => [
        "array" => "Поле :attribute має містити більше, ніж :value елементів.",
        "file" => "Поле :attribute має бути більше ніж :value кілобайт.",
        "numeric" => "Поле :attribute повинно бути більше ніж :value.",
        "string" => "Поле :attribute повинно містити більше ніж :value символів.",
    ],
    "gte" => [
        "array" => "Поле :attribute має мати :value елементів або більше.",
        "file" => "Поле :attribute повинно мати розмір не менше :value кілобайт.",
        "numeric" => "Поле :attribute повинно бути більшим або дорівнювати :value.",
        "string" => "Поле :attribute має містити не менше ніж :value символів.",
    ],
    "image" => "Поле :attribute повинно бути зображенням.",
    "in" => "Вибране значення для :attribute є неприпустимим.",
    "in_array" => "Поле :attribute не існує в :other.",
    "integer" => "Поле :attribute повинно бути цілим числом",
    "ip" => "Поле :attribute повинно бути дійсною IP-адресою.",
    "ipv4" => "Поле :attribute повинно містити дійсну IPv4 адресу.",
    "ipv6" => "Поле :attribute повинно містити дійсну адресу IPv6.",
    "json" => "Поле :attribute має містити коректний рядок у форматі JSON.",
    "lowercase" => "Поле :attribute повинно містити лише символи в нижньому регістрі.",
    "lt" => [
        "array" => "Поле :attribute має містити менше ніж :value елементів.",
        "file" => "Поле :attribute повинно бути менше, ніж :value кілобайт.",
        "numeric" => "Поле :attribute повинно бути менше ніж :value.",
        "string" => "Поле :attribute повинно мати менше, ніж :value символів.",
    ],
    "lte" => [
        "array" => "Поле :attribute не може містити більше, ніж :value елементів.",
        "file" => "Поле :attribute повинно бути не більше, ніж :value кілобайт.",
        "numeric" => "Поле :attribute повинно бути не більше, ніж :value.",
        "string" => "Поле :attribute повинно бути не більше, ніж :value символів.",
    ],
    "mac_address" => "Поле :attribute повинно бути дійсною MAC-адресою.",
    "max" => [
        "array" => "Поле :attribute повинно містити не більше ніж :max елементів.",
        "file" => "Розмір поля :attribute повинен бути не більше ніж :max кілобайт.",
        "numeric" => "Значення поля :attribute повинно бути не більше ніж :max.",
        "string" => "Довжина поля :attribute повинна бути не більше ніж :max символів.",
    ],
    "max_digits" => "Поле :attribute не повинно мати більше ніж :max цифр.",
    "mimes" => "Поле :attribute повинно бути файлом типу: :values.",
    "mimetypes" => "Поле :attribute повинно бути файлом типу: :values.",
    "min" => [
        "array" => "Поле :attribute повинно містити принаймні :min елементів.",
        "file" => "Розмір поля :attribute повинен бути не менше ніж :min кілобайт.",
        "numeric" => "Значення поля :attribute повинно бути не менше ніж :min.",
        "string" => "Довжина поля :attribute повинна бути не менше ніж :min символів.",
    ],
    "min_digits" => "Поле :attribute повинно мати принаймні :min цифр.",
    "multiple_of" => "Поле :attribute повинно бути кратним :value.",
    "not_in" => "Вибране значення для :attribute недійсне.",
    "not_regex" => "Формат :attribute недійсний.",
    "numeric" => "Поле :attribute повинно бути числовим.",
    "password" => [
        "letters" => "Поле :attribute повинно містити принаймні одну літеру.",
        "mixed" => "Поле :attribute має містити хоча б одну маленьку та велику літери.",
        "numbers" => "Поле :attribute має містити хоча б одну цифру.",
        "symbols" => "Поле :attribute має містити хоча б один спеціальний символ.",
        "uncompromised" => "Цей пароль скомпрометований і потрапив у витоки даних. Будь ласка, оберіть інший.",
    ],
    "present" => "Поле :attribute повинно бути присутнє.",
    "prohibited" => "Поле :attribute заборонено.",
    "prohibited_if" => "Поле :attribute заборонено, коли :other є :value.",
    "prohibited_unless" => "Поле :attribute заборонено, якщо :other не є в :values.",
    "prohibits" => "Поле :attribute забороняє наявність :other.",
    "regex" => "Формат поля :attribute недійсний.",
    "required" => "Поле :attribute є обов'язковим.",
    "required_array_keys" => "Поле :attribute повинно містити записи для: :values.",
    "required_if" => "Поле :attribute є обов'язковим, коли :other є :value.",
    "required_if_accepted" => "Поле :attribute є обов'язковим, коли :other прийнято.",
    "required_unless" => "Поле :attribute є обов'язковим, якщо :other не знаходиться в :values.",
    "required_with" => "Поле :attribute є обов'язковим, коли :values присутнє.",
    "required_with_all" => "Поле :attribute є обов'язковим, коли присутні всі :values.",
    "required_without" => "Поле :attribute є обов'язковим, коли :values не присутнє.",
    "required_without_all" => "Поле :attribute є обов'язковим, коли немає жодного з :values.",
    "same" => "Поля :attribute та :other повинні збігатися.",
    "size" => [
        "array" => "Поле :attribute повинно містити :size елементів.",
        "file" => "Поле :attribute повинно мати розмір :size кілобайт.",
        "numeric" => "Поле :attribute повинно бути :size.",
        "string" => "Поле :attribute повинно бути довжиною :size символів.",
    ],
    "starts_with" => "Поле :attribute повинно починатися з одного з наступних значень: :values.",
    "string" => "Поле :attribute повинно бути рядком.",
    "timezone" => "Поле :attribute повинно бути дійсною часовою зоною.",
    "unique" => "Поле :attribute вже використовується.",
    "uploaded" => "Не вдалося завантажити файл :attribute.",
    "uppercase" => "Поле :attribute повинно містити лише великі літери.",
    "url" => "Поле :attribute повинно бути дійсною URL-адресою.",
    "ulid" => "Поле :attribute повинно бути дійсним ULID.",
    "uuid" => "Поле :attribute повинно бути дійсним UUID.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    "custom" => [
        // Complain
        "complain" => [
            "array"   => "Неправильний формат даних скарги.",
        ],
        "complain.url" => [
            "required" => "Url сторінки не передано.",
            "url" => "Url сторінки має бути дійсним посиланням.",
        ],
        "complain.theme" => [
            "required" => "Оберіть тему скарги.",
        ],
        "complain.message" => [
            "required" => "Вкажіть текст скарги.",
            "max" => "Опис скарги не має бути довшим за :max символів."
        ],

        // Appeal
        "appeal.theme" => [
            "required" => "Оберіть тему звернення.",
        ],
        "appeal.message" => [
            "required" => "Вкажіть текст звернення.",
            "max" => "Текст звернення не має бути довшим за :max символів."
        ],

        // BG Manager
        "bg_image" => [
            "required" => "Оберіть фонове зображення.",
        ],
        "material_type" => [
            "required" => "Тип матеріалу не передано.",
            "in" => "Неправильний тип матеріалу.",
        ],
        "material_id" => [
            "required" => "Ідентифікатор матеріалу не передано.",
        ],

        // Media Manager
        "media" => [
            "required"   => "Оберіть медіа.",
            "array"   => "Неправильний формат даних медіа.",
        ],
        "media.id" => [
            "string"   => "Неправильний формат ідентифікатора медіа.",
        ],
        "media.old_name" => [
            "max"   => "Стара назва файлу не повинна перевищувати :max символів.",
        ],
        "media.new_name" => [
            "max"   => "Нова назва файлу не повинна перевищувати :max символів.",
            "not_regex" => "Назва містить заборонені символи (\, /, :, *, ?, \", <, >, | тощо).",
        ],
        "media.path" => [
            "string" => "Шлях до медіа має бути у правильному форматі.",
        ],
        "media.files.*" => [
            "mimes" => "Файл має непідтримуваний тип. Дозволені формати: :values.",
            "max"   => "Файл занадто великий. Максимальний розмір не повинен перевищувати 250 МБ.",
        ],
        "media.order" => [
            "required" => "Не вдалося визначити порядок сортування медіа файлів.",
            "json"     => "Порядок сортування файлів має бути у правильному форматі (JSON).",
        ],
        "media.order_items.*.name" => [
            "required" => "Назва файла в даних сортування обов'язкова.",
            "max"     => "Назва файла в даних сортування не повинна перевищувати :max символів.",
        ],
        "media.name.max_bytes" => "Назва файлу медіа занадто довга.",

        // Menu
        "menu" => [
            "array"   => "Неправильний формат даних menu.",
        ],
        "menu.locale" => [
            "in"   => "Ця мова не використовується в Системі.",
        ],
        "menu.item_id" => [
            "exists"   => "Пункт меню з переданим ідентифікатором не існує.",
        ],
        "menu.parent_id" => [
            "exists"   => "Батьківський пункт меню з переданим ідентифікатором не існує.",
        ],
        "menu.title" => [
            "required"   => "Вкажіть назву пункта меню.",
        ],
        "menu.order_position" => [
            "required"   => "Вкажіть порядок пункта меню.",
        ],

        // Statistic
        "key" => [
            "required" => "Ключ не передано.",
            "in" => "Ключ має бути дійсним.",
        ],
        "model_type" => [
            "required" => "Тип моделі не передано.",
            "in" => "Неіснуючий тип моделі.",
        ],
        "model_id" => [
            "required" => "Ідентифікатор моделі не передано.",
            "exists" => "Модель не існує.",
        ],

        // Consumer Settings
        "consumerType" => [
            "required" => "Тип користувача не передано.",
            "in" => "Неіснуючий тип користувача.",
        ],

        // Other
        "unique_value" => [
            "unique" => "Дане значення вже використовується.",
        ],
        "dotTargetKey" => [
            "required" => "Ключ не передано.",
        ],
        "alias" => [
            "regex" => "Неправильний формат аліасу.",
            "unique" => "Аліас вже використовується.",
            "exists" => "Матеріал не існує.",
        ],
        "id" => [
            "exists" => "Модель не існує.",
        ],
        "type" => [
            "required" => "Тип моделі не передано",
            "in" => "Неправильний тип моделі.",
        ],
        "locale" => [
            "required" => "Оберіть мову.",
            "in" => "Ця мова не використовується в Системі.",
        ],
        "layout" => [
            "required" => "Налаштування макету не передано.",
            "json" => "Неправильний формат даних макету.",
        ],
        "password" => [
            "required" => "Вкажіть пароль.",
            "min" => "Пароль має містити принаймні :min символів.",
            "confirmed" => "Повторіть вказаний пароль.",
        ],
        "permissions" => [
            "required" => "Вкажіть дозволи цього користувача.",
            "json" => "Неправильний формат даних дозволів.",
        ],
        "email" => [
            "required" => "Вкажіть email.",
            "email" => "Вкажіть правильний формат email.",
            "regex" => "Вкажіть правильний формат email.",
            "unique" => "Цей email вже використовується.",
            "required_if" => "Вкажіть номер телефону або email.",
        ],
        "phone" => [
            "required" => "Вкажіть номер телефону.",
            "json" => "Неправильний формат даних номеру телефону.",
            "required_if" => "Вкажіть номер телефону або email.",
        ],
        "emails" => [
            "json" => "Неправильний формат даних поштових скриньок.",
        ],
        "phones" => [
            "json" => "Неправильний формат даних номерів телефону.",
        ],
        "links" => [
            "json" => "Неправильний формат даних посилань.",
        ],
        "location" => [
            "json" => "Неправильний формат даних розташування.",
        ],
        "social_networks" => [
            "json" => "Неправильний формат даних соцмереж.",
        ],
        "title" => [
            "required" => "Вкажіть заголовок.",
        ],
        "description" => [
            "required" => "Вкажіть опис.",
            "max" => "Опис має містити не більше ніж :max символів.",
        ],
        "category_id" => [
            "exists" => "Категорія не існує.",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    "attributes" => [
        // Complain
        "complain" => "скарга",
        "complain.url" => "URL матеріалу",
        "complain.theme" => "тема скарги",
        "complain.message" => "текст скарги",

        // Appeal
        "appeal.theme" => "тема звернення",
        "appeal.message" => "текст звернення",

        // BG Manager
        "bg_image" => "фонове зображення",
        "material_type" => "тип матеріалу",
        "material_id" => "ідентифікатор матеріалу",

        // Media Manager
        "media" => "медіа",
        "media.id" => "ідентифікатор файла",
        "media.old_name" => "стара назва файла",
        "media.new_name" => "нова назва файла",
        "media.path" => "шлях до файлів",
        "media.files" => "файли",
        "media.files.*" => "файл",
        "media.order" => "порядок сортування",

        // Menu
        "menu" => "меню",
        "menu.locale" => "мова",
        "menu.item_id" => "ідентифікатор пункта меню",
        "menu.parent_id" => "ідентифікатор батьківського пункта меню",
        "menu.title" => "назва пункта меню",
        "menu.order_position" => "позиція пункта меню",

        // Statistic
        "key" => "ключ статистики",
        "model_type" => "тип моделі",
        "model_id" => "ідентифікатор моделі",

        // Consumer Settings
        "consumerType" => "Тип користувача",

        // Other
        "dotTargetKey" => "ключ налаштувань",
        "layout" => "макет",
        "emails" => "поштові скриньки",
        "phones" => "номери телефону",
        "links" => "посилання",
        "email" => "email",
        "phone" => "номер телефону",
        "password" => "пароль",
        "password_confirmation" => "повтор паролю",
        "description" => "опис",
        "permissions" => "дозволи",
        "location" => "розташування",
        "social_networks" => "соцмережі",
        "id" => "ідентифікатор",
        "category_id" => "категорія",
        "type" => "тип моделі",
        "title" => "заголовок",
        "locale" => "мова",
        "unique_value" => "дане значення",
        "alias" => "аліас",
    ],
];
