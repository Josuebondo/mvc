<?php

namespace Core;

/**
 * Classe Validator - Validation de formulaires
 */
class Validator
{
    protected array $errors = [];
    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data ?: $_POST;
    }

    /**
     * Valider les données
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {
            $rules_array = explode('|', $fieldRules);

            foreach ($rules_array as $rule) {
                $this->validateRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Valider une règle spécifique
     */
    protected function validateRule(string $field, string $rule): void
    {
        $value = $this->data[$field] ?? '';

        if (strpos($rule, ':') !== false) {
            [$rule, $param] = explode(':', $rule, 2);
        }

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, ucfirst($field) . " est requis");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Email invalide");
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $param) {
                    $this->addError($field, ucfirst($field) . " doit avoir au moins $param caractères");
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $param) {
                    $this->addError($field, ucfirst($field) . " ne peut pas dépasser $param caractères");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, ucfirst($field) . " doit être numérique");
                }
                break;

            case 'confirmed':
                if ($value !== ($this->data[$field . '_confirmation'] ?? '')) {
                    $this->addError($field, ucfirst($field) . " ne correspond pas");
                }
                break;

            case 'unique':
                // $param should be "table,column"
                $this->validateUnique($field, $param);
                break;

            case 'match':
                if ($value !== ($this->data[$param] ?? '')) {
                    $this->addError($field, ucfirst($field) . " ne correspond pas à " . ucfirst($param));
                }
                break;
        }
    }

    /**
     * Valider l'unicité en BD
     */
    protected function validateUnique(string $field, string $param): void
    {
        $value = $this->data[$field] ?? '';
        if (empty($value)) return;

        [$table, $column] = explode(',', $param);
        $db = new \Core\Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

        $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
        $result = $db->fetch($sql, [$value]);

        if ($result['count'] > 0) {
            $this->addError($field, ucfirst($field) . " existe déjà");
        }
    }

    /**
     * Ajouter une erreur
     */
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    /**
     * Obtenir les erreurs
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Vérifier s'il y a des erreurs
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Afficher les erreurs
     */
    public function displayErrors(): string
    {
        if (empty($this->errors)) return '';

        $html = '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">';
        $html .= '<strong>Erreurs de validation:</strong><ul style="margin: 10px 0 0 20px;">';

        foreach ($this->errors as $field => $message) {
            $html .= '<li>' . escape($message) . '</li>';
        }

        $html .= '</ul></div>';
        return $html;
    }

    /**
     * Obtenir les données validées
     */
    public function getData(): array
    {
        return $this->data;
    }
}
