<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Data :attribute harus diterima.',
    'active_url' => 'Data :attribute bukan URL yang valid.',
    'after' => 'Data :attribute harus tanggal setelah :date.',
    'after_or_equal' => 'Data :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Data :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Data :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Data :attribute hanya boleh berisi huruf dan angka.',
    'array' => 'Data :attribute harus berupa array.',
    'before' => 'Data :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'Data :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => 'Data :attribute harus antara :min dan :max.',
        'file' => 'Data :attribute harus antara :min dan :max kilobytes.',
        'string' => 'Data :attribute harus antara :min dan :max characters.',
        'array' => 'Data :attribute harus ada antara :min dan :max items.',
    ],
    'boolean' => 'Data :attribute harus benar atau salah.',
    'confirmed' => 'Data :attribute konfirmasi tidak cocok.',
    'date' => 'Data :attribute bukan tanggal yang valid.',
    'date_equals' => 'Data :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Data :attribute tidak sesuai dengan formatnya :format.',
    'different' => 'Data :attribute dan :other harus berbeda.',
    'digits' => 'Data :attribute harus :digits digits.',
    'digits_between' => 'Data :attribute harus antara :min dan :max digits.',
    'dimensions' => 'Data :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Data :attribute memiliki nilai duplikat.',
    'email' => 'Data :attribute harus alamat email yang valid.',
    'ends_with' => 'Data :attribute harus diakhiri dengan salah satu dari berikut ini: :values.',
    'exists' => 'Data terpilih :attribute tidak valid.',
    'file' => 'Data :attribute harus sebuah file.',
    'filled' => 'Data :attribute harus memiliki nilai.',
    'gt' => [
        'numeric' => 'Data :attribute harus lebih besar dari :value.',
        'file' => 'Data :attribute harus lebih besar dari :value kilobytes.',
        'string' => 'Data :attribute harus lebih besar dari :value characters.',
        'array' => 'Data :attribute harus memiliki lebih dari :value items.',
    ],
    'gte' => [
        'numeric' => 'Data :attribute harus lebih besar dari atau sama :value.',
        'file' => 'Data :attribute harus lebih besar dari atau sama :value kilobytes.',
        'string' => 'Data :attribute harus lebih besar dari atau sama :value characters.',
        'array' => 'Data :attribute harus memiliki :value item atau lebih.',
    ],
    'image' => 'Data :attribute harus sebuah gambar.',
    'in' => 'Data terpilih :attribute tidak valid.',
    'in_array' => 'Data :attribute tidak ada di :other.',
    'integer' => 'Data :attribute harus sebuah integer.',
    'ip' => 'Data :attribute harus valid IP address.',
    'ipv4' => 'Data :attribute harus valid IPv4 address.',
    'ipv6' => 'Data :attribute harus valid IPv6 address.',
    'json' => 'Data :attribute harus valid JSON string.',
    'lt' => [
        'numeric' => 'Data :attribute harus kurang dari :value.',
        'file' => 'Data :attribute harus kurang dari :value kilobytes.',
        'string' => 'Data :attribute harus kurang dari :value characters.',
        'array' => 'Data :attribute harus memiliki kurang dari :value items.',
    ],
    'lte' => [
        'numeric' => 'Data :attribute harus kurang dari atau sama :value.',
        'file' => 'Data :attribute harus kurang dari atau sama :value kilobytes.',
        'string' => 'Data :attribute harus kurang dari atau sama :value characters.',
        'array' => 'Data :attribute tidak boleh lebih dari :value items.',
    ],
    'max' => [
        'numeric' => 'Data :attribute mungkin tidak lebih besar dari :max.',
        'file' => 'Data :attribute mungkin tidak lebih besar dari :max kilobytes.',
        'string' => 'Data :attribute mungkin tidak lebih besar dari :max characters.',
        'array' => 'Data :attribute mungkin tidak memiliki lebih dari :max items.',
    ],
    'mimes' => 'Data :attribute harus sebuah jenis file: :values.',
    'mimetypes' => 'Data :attribute harus sebuah jenis file: :values.',
    'min' => [
        'numeric' => 'Data :attribute harus setidaknya :min.',
        'file' => 'Data :attribute harus setidaknya :min kilobytes.',
        'string' => 'Data :attribute harus setidaknya :min characters.',
        'array' => 'Data :attribute harus memiliki setidaknya :min items.',
    ],
    'not_in' => 'Data terpilih :attribute tidak valid.',
    'not_regex' => 'Data :attribute format tidak valid.',
    'numeric' => 'Data :attribute harus berupa angka.',
    'password' => 'Data Kata sandi salah.',
    'present' => 'Data :attribute harus disajikan.',
    'regex' => 'Data :attribute format tidak valid.',
    'required' => 'Data :attribute dibutuhkan.',
    'required_if' => 'Data :attribute dibutuhkan ketika :other adalah :value.',
    'required_unless' => 'Data :attribute dibutuhkan kecuali :other adalah :values.',
    'required_with' => 'Data :attribute dibutuhkan ketika :values adalah disajikan.',
    'required_with_all' => 'Data :attribute dibutuhkan ketika :values disajikan.',
    'required_without' => 'Data :attribute dibutuhkan ketika :values adalah tidak disajikan.',
    'required_without_all' => 'Data :attribute dibutuhkan ketika tidak ada dari :values disajikan.',
    'same' => 'Data :attribute dan :other harus cocok.',
    'size' => [
        'numeric' => 'Data :attribute harus :size.',
        'file' => 'Data :attribute harus :size kilobytes.',
        'string' => 'Data :attribute harus :size characters.',
        'array' => 'Data :attribute harus berisi :size items.',
    ],
    'starts_with' => 'Data :attribute harus dimulai dengan salah satu dari berikut ini: :values.',
    'string' => 'Data :attribute harus sebuah string.',
    'timezone' => 'Data :attribute harus zona yang valid.',
    'unique' => 'Data :attribute telah terdaftar.',
    'uploaded' => 'Data :attribute gagal diunggah.',
    'url' => 'Data :attribute format tidak valid.',
    'uuid' => 'Data :attribute harus valid UUID.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [],

];
