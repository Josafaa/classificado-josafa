<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Plan extends Entity

{
    private const INTERVAL_MONTHLY = 1; //Mensal
    private const INTERVAL_QUARTERLY = 3; // Trimesral
    private const INTERVAL_SEMESTER = 6; //Semestral
    private const INTERVAL_YEARLY = 12; // ANUAL

    public const OPTION_MONTHLY = 'monthly';
    public const OPTION_QUARTERLY = 'quarterly'; 
    public const OPTION_SEMESTER = 'semester'; 
    public const OPTION_YEARLY = 'yearly'; 

    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'plan_id'           => 'integer',
        'adverts'           => '?integer',
        'is_highlighted'    => 'boolean',
        'recorrence'        => 'string', // Adicionando a propriedade
    ];

    public function setValue(string $value) 
    {
        $this->attributes['value'] = str_replace(',','', $value);

        return $this;
    }

    public function setAdverts(string $adverts) 
    {
        $this->attributes['adverts'] = $adverts ? (int) $adverts : null;

        return $this;
    }

    public function setIsHighlighted(string|int $isHighlighted)
    {
        $this->attributes['is_highlighted'] = (bool) $isHighlighted; // Converte para booleano
        return $this;
    }

    public function setIntervalRepeats()
    {
        // Envia a cobrança para o Safe2pay até que o plano seja cancelado
        $this->repeats = null;

        $this->attributes['interval'] = match ($this->attributes['recorrence'] ?? null) {
            self::OPTION_MONTHLY   => self::INTERVAL_MONTHLY,
            self::OPTION_QUARTERLY => self::INTERVAL_QUARTERLY,
            self::OPTION_SEMESTER  => self::INTERVAL_SEMESTER,
            self::OPTION_YEARLY    => self::INTERVAL_YEARLY,
            default => throw new \InvalidArgumentException("Unsupported recurrence: {$this->attributes['recorrence']}"),
        };

        return $this;
    }

    public function recover() 
    {
        $this->attributes['deleted_at'] = null;
    }


    //verificar metodo de construção sem o lang
    public function ishighlighted() 
    {
        return $this->attributes['is_highlighted'];
    }

    //verificar metodo de construção sem o lang
    public function adverts() 
    {
        return $this->attributes['adverts'];
    }

    //Talvez remover...
    public function details() 
    {
        /**
         * @todo alterar para exibier conforme o idioma
         */

         return number_to_currency($this->value, 'BRL', 'pt-BR', 2) . ' /'.$this->recorence;
    }
}
