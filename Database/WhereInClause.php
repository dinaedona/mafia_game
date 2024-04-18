<?php
class WhereInClause
{
    private string $placeholder;
    private string $types;
    /** @var int[]|string[]|float[] */
    private array $parameters;

    /**
     * @param string[] $parameters
     * @return WhereInClause
     */
    public static function fromStrings(array $parameters): WhereInClause
    {
        return new WhereInClause($parameters, 's');
    }

    /**
     * @param int[] $parameters
     * @return WhereInClause
     */
    public static function fromInts(array $parameters): WhereInClause
    {
        return new WhereInClause($parameters, 'i');
    }

    /**
     * @param array  $parameters
     * @param string $type
     */
    public function __construct(array $parameters, string $type)
    {
        $this->placeholder = str_repeat("?,", count($parameters) - 1) . "?";
        $this->types = str_repeat($type, count($parameters));
        $this->parameters = array_values($parameters);
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getTypes(): string
    {
        return $this->types;
    }

    /**
     * @return int[]|string[]|float[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
