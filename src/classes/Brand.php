<?php
    class Brand {
        private $db = null;
        function  __construct($db) {
            $this->db = $db;
        }

        function getAllBrands() {
            $sql = "SELECT 
                id, 
                organization_id, 
                name, 
                brand_logo, 
                brand_profile_image, 
                active,
                created_at
            FROM brands";

            $stmt = $this->db->query($sql);
            $brands = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $brands;
        }

        function getBrand($brand_id) {
            
            $sql = "SELECT 
                id, 
                organization_id, 
                name, 
                brand_logo, 
                brand_profile_image, 
                active,
                created_at
            FROM brands WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $brand_id);
            $stmt->execute();
            $brand = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($brand) {
                return $brand;
            } else {
                throw new PDOException('Brand not found', 404 );
            }

            
        }
    };
?>