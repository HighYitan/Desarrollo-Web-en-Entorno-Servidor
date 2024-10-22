<?php
    namespace models;

    class Customer extends Model{
        protected static $table = "customers"; // Definir la taula associada a la classe
        public function __construct(private int $customer_id,
                                    private ?string $cust_first_name = null,
                                    private ?string $cust_last_name = null,
                                    private ?string $cust_streets_address = null,
                                    private ?string $cust_postal_code = null,
                                    private ?string $cust_city = null,
                                    private ?string $cust_state = null,
                                    private ?string $cust_country = null,
                                    private ?string $phone_numbers = null,
                                    private ?string $nls_language = null,
                                    private ?string $nls_territory = null,
                                    private ?float $credit_limit = null,
                                    private ?string $cust_email = null,
                                    private ?int $account_mgr_id = null,
                                    private ?string $cust_geo_location = null,
                                    private ?string $date_of_birth = null,
                                    private ?string $marital_Status = null,
                                    private ?string $gender = null,
                                    private ?string $income_level = null){}
    }
?>