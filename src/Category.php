<?php

    class Category
    {

        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO categories (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        static function getAll()
        {
            $statement = $GLOBALS['DB']->query("SELECT * FROM categories;");
            $all_categories = array();
            foreach($statement as $category){
                $name = $category['name'];
                $id = $category['id'];
                $new_category = new Category($name, $id);
                array_push($all_categories, $new_category);
            }
            return $all_categories;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories *;");
        }

        static function find($search_id)
        {
            $found_category = null;
            $categories = Category::getAll();
            foreach($categories as $category){
                $category_id = $category->getId();
                if ($category_id == $search_id) {
                    $found_category = $category;
                }
            }
            return $found_category;
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE categories SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM categories WHERE id = {$this->getId()};");
        }

    }

?>
