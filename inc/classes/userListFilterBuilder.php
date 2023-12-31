<?php

    class userListFilterBuilder {

        private $optionGroups;

        public function __construct() {
            $this->optionGroups = [];
        }
        public function addOptionGroup($optionGroup=[]) {
            
            $this->optionGroups[] = $optionGroup;
        }

        public function userListFilterCheckbox($optionParent = '', $optionName = '', $filterTarget = 'cell') {
            
            $optGroupClass = ($optionParent) ? $optionParent : $optionName;

            return '<label class="filter-nav__checkbox-label" for="filter-option-' . $optionName . '">' .
                        '<input class="filter-nav__checkbox-input filter-nav__checkbox--option-group-'. $optGroupClass .'" id="filter-option-' . $optionName . '"
                            type="checkbox" name="' . $optionName . '"
                            onChange="changeUserListFilterOptions(`' . $optionParent . '`, this, `' . $filterTarget . '`)">' .
                            $optionName .
                    '</label>';
        }

        public function render() {
            
            $filter = '<div class="user-list-filter__container">';
            foreach ($this->optionGroups as $optionGroup) {
                $filter .= '<div class="user-list-filter__group">';
                
                foreach ($optionGroup as $option) {
                    $filter .= $option;
                }
                $filter .= '</div>';
            }
            $filter .= '</div>';
            echo $filter;
        }
    }


?>