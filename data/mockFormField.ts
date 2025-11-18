import type { FormGroup } from "../types";

export const mockFormField: FormGroup[] = [
    {
        "title": "Основная информация",
        "type": "main",
        "items": [
            {
                "type": "text",
                "name": "title",
                "title": "Название блюда",
                "required": true,
                "placeholder": "Название блюда"
            },
            {
                "type": "number",
                "name": "cookingTime",
                "title": "Время приготовления",
                "required": true,
                "placeholder": "Время приготовления"  
            },
            {
                "type": "number",
                "name": "servings",
                "title": "Количество порций",
                "required": true,
                "placeholder": "Количество порций"
            },
            {
                "type": "select",
                "name": "difficulty",
                "title": "Сложность",
                "options": [
                    {
                        "value": "легко",
                        "label": "Легкая"
                    },
                    {
                        "value": "средне",
                        "label": "Средняя"
                    },
                    {
                        "value": "сложно",
                        "label": "Сложная"
                    }
                ],
                "required": true,
                "placeholder": "Сложность"
            },
            {
                "type": "textarea",
                "name": "description",
                "title": "Описание блюда",
                "required": true,
                "placeholder": "Добавьте описание"
            },
            {
                "type": "text",
                "name": "imageUrl",
                "title": "Ссылка на изображение",
                "required": false,
                "placeholder": "Вставьте ссылку на изображение"
            }
        ]
    },
    {
        "title": "Ингредиенты",
        "type": "ingredients",
        "ingredients": [     
            {
                items: [
                    {
                        "type": "text",
                        "name": "name",
                        "title": "Название",
                        "required": true,
                        "placeholder": "Введите название ингредиента"
                    },
                    {
                        "type": "number",
                        "name": "quantity",
                        "title": "Количество",
                        "required": true,
                        "placeholder": "5"
                    },
                    {
                        "type": "select",
                        "name": "unit",
                        "title": "Единица измерения",
                        "options": [
                            { value: 'г', label: 'Граммы' },
                            { value: 'кг', label: 'Килограммы' },
                            { value: 'мл', label: 'Миллилитры' },
                            { value: 'л', label: 'Литры' },
                            { value: 'шт', label: 'Штуки' },
                            { value: 'ст.л.', label: 'Столовые ложки' },
                            { value: 'ч.л.', label: 'Чайные ложки' },
                            { value: 'по вкусу', label: 'По вкусу' }
                        ],
                        "required": true,
                        "placeholder": "Единицы измерения"
                    }
                ]
            }           
        ]
    },
    {
        "title": "Этапы приготовления",
        "type": "steps",
        "items": [
            {
                "type": "textarea",
                "name": "step",
                "title": "Этап",
                "required": true,
                "placeholder": "Опишите этап приготовления"
            }
        ]
    }
]