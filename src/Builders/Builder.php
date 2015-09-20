<?php

namespace LaravelDoctrine\Fluent\Builders;

use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Fluent;
use LaravelDoctrine\Fluent\Relations\ManyToMany;
use LaravelDoctrine\Fluent\Relations\ManyToOne;
use LaravelDoctrine\Fluent\Relations\OneToMany;
use LaravelDoctrine\Fluent\Relations\OneToOne;
use LaravelDoctrine\Fluent\Relations\Relation;
use LogicException;

class Builder extends AbstractBuilder implements Fluent
{
    /**
     * @var array|Buildable[]
     */
    protected $queued = [];

    /**
     * @var array
     */
    protected $macros = [];

    /**
     * @param string|callable $name
     * @param callable|null   $callback
     *
     * @return Table
     */
    public function table($name, callable $callback = null)
    {
        if ($this->isEmbeddedClass()) {
            throw new LogicException;
        }

        $table = new Table($this->builder);

        if (is_callable($name)) {
            $name($table);
        } else {
            $table->setName($name);
        }

        if (is_callable($callback)) {
            $callback($table);
        }

        return $table;
    }

    /**
     * @param callable|null $callback
     *
     * @return Entity
     */
    public function entity(callable $callback = null)
    {
        if ($this->isEmbeddedClass()) {
            throw new LogicException;
        }

        $entity = new Entity($this->builder);

        if (is_callable($callback)) {
            $callback($entity);
        }

        return $entity;
    }

    /**
     * @param          $type
     * @param          $name
     * @param callable $callback
     *
     * @return Field
     */
    public function field($type, $name, callable $callback = null)
    {
        $field = Field::make($this->builder, $type, $name);

        if (is_callable($callback)) {
            $callback($field);
        }

        $this->queue($field);

        return $field;
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function increments($name, callable $callback = null)
    {
        if ($this->isEmbeddedClass()) {
            throw new LogicException;
        }

        return $this->integer($name, $callback)->primary()->unsigned()->autoIncrement();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function smallIncrements($name, callable $callback = null)
    {
        if ($this->isEmbeddedClass()) {
            throw new LogicException;
        }

        return $this->smallInteger($name, $callback)->primary()->unsigned()->autoIncrement();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function bigIncrements($name, callable $callback = null)
    {
        if ($this->isEmbeddedClass()) {
            throw new LogicException;
        }

        return $this->bigInteger($name, $callback)->primary()->unsigned()->autoIncrement();
    }

    /**
     * @param          $name
     * @param callable $callback
     *
     * @return Field
     */
    public function string($name, callable $callback = null)
    {
        return $this->field(Type::STRING, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function text($name, callable $callback = null)
    {
        return $this->field(Type::TEXT, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function integer($name, callable $callback = null)
    {
        return $this->field(Type::INTEGER, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function smallInteger($name, callable $callback = null)
    {
        return $this->field(Type::SMALLINT, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function bigInteger($name, callable $callback = null)
    {
        return $this->field(Type::BIGINT, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function unsignedSmallInteger($name, callable $callback = null)
    {
        return $this->smallInteger($name, $callback)->unsigned();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function unsignedInteger($name, callable $callback = null)
    {
        return $this->integer($name, $callback)->unsigned();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function unsignedBigInteger($name, callable $callback = null)
    {
        return $this->bigInteger($name, $callback)->unsigned();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function float($name, callable $callback = null)
    {
        return $this->field(Type::FLOAT, $name, $callback)->precision(8)->scale(2);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function decimal($name, callable $callback = null)
    {
        return $this->field(Type::DECIMAL, $name, $callback)->precision(8)->scale(2);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function boolean($name, callable $callback = null)
    {
        return $this->field(Type::BOOLEAN, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function jsonArray($name, callable $callback = null)
    {
        return $this->field(Type::JSON_ARRAY, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function date($name, callable $callback = null)
    {
        return $this->field(Type::DATE, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function dateTime($name, callable $callback = null)
    {
        return $this->field(Type::DATETIME, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function dateTimeTz($name, callable $callback = null)
    {
        return $this->field(Type::DATETIMETZ, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function time($name, callable $callback = null)
    {
        return $this->field(Type::TIME, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function timestamp($name, callable $callback = null)
    {
        return $this->field(Type::DATETIME, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function timestampTz($name, callable $callback = null)
    {
        return $this->field(Type::DATETIMETZ, $name, $callback);
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function binary($name, callable $callback = null)
    {
        return $this->field(Type::BINARY, $name, $callback)->nullable();
    }

    /**
     * @param string        $name
     * @param callable|null $callback
     *
     * @return Field
     */
    public function rememberToken($name = 'rememberToken', callable $callback = null)
    {
        return $this->string($name, $callback)->nullable()->length(100);
    }

    /**
     * @param string        $field
     * @param string        $entity
     * @param callable|null $callback
     *
     * @return OneToOne
     */
    public function hasOne($field, $entity, callable $callback = null)
    {
        return $this->oneToOne($field, $entity, $callback);
    }

    /**
     * @param string        $field
     * @param string        $entity
     * @param callable|null $callback
     *
     * @return OneToOne
     */
    public function oneToOne($field, $entity, callable $callback = null)
    {
        return $this->addRelation(
            new OneToOne(
                $this->builder,
                $this->namingStrategy,
                $field,
                $entity
            ),
            $callback
        );
    }

    /**
     * @param               $field
     * @param               $entity
     * @param callable|null $callback
     *
     * @return ManyToOne
     */
    public function belongsTo($field, $entity, callable $callback = null)
    {
        return $this->manyToOne($field, $entity, $callback);
    }

    /**
     * @param string   $field
     * @param string   $entity
     * @param callable $callback
     *
     * @return ManyToOne
     */
    public function manyToOne($field, $entity, callable $callback = null)
    {
        return $this->addRelation(
            new ManyToOne(
                $this->builder,
                $this->namingStrategy,
                $field,
                $entity
            ),
            $callback
        );
    }

    /**
     * @param               $field
     * @param               $entity
     * @param callable|null $callback
     *
     * @return OneToMany
     */
    public function hasMany($field, $entity, callable $callback = null)
    {
        return $this->oneToMany($field, $entity, $callback);
    }

    /**
     * @param string   $field
     * @param string   $entity
     * @param callable $callback
     *
     * @return OneToMany
     */
    public function oneToMany($field, $entity, callable $callback = null)
    {
        return $this->addRelation(
            new OneToMany(
                $this->builder,
                $this->namingStrategy,
                $field,
                $entity
            ),
            $callback
        );
    }

    /**
     * @param               $field
     * @param               $entity
     * @param callable|null $callback
     *
     * @return ManyToMany
     */
    public function belongsToMany($field, $entity, callable $callback = null)
    {
        return $this->manyToMany($field, $entity, $callback);
    }

    /**
     * @param string   $field
     * @param string   $entity
     * @param callable $callback
     *
     * @return ManyToMany
     */
    public function manyToMany($field, $entity, callable $callback = null)
    {
        return $this->addRelation(
            new ManyToMany(
                $this->builder,
                $this->namingStrategy,
                $field,
                $entity
            ),
            $callback
        );
    }

    /**
     * Adds a custom relation to the entity.
     *
     * @param \LaravelDoctrine\Fluent\Relations\Relation $relation
     * @param callable|null                              $callback
     *
     * @return Relation
     */
    public function addRelation(Relation $relation, callable $callback = null)
    {
        if (is_callable($callback)) {
            $callback($relation);
        }

        $this->queue($relation);

        return $relation;
    }

    /**
     * @param string        $field
     * @param string        $embeddable
     * @param callable|null $callback
     *
     * @return Embedded
     */
    public function embed($field, $embeddable, callable $callback = null)
    {
        $embedded = new Embedded(
            $this->builder,
            $field,
            $embeddable
        );

        if (is_callable($callback)) {
            $callback($embedded);
        }

        $this->queue($embedded);

        return $embedded;
    }

    /**
     * @return bool
     */
    public function isEmbeddedClass()
    {
        return $this->builder->getClassMetadata()->isEmbeddedClass;
    }

    /**
     * @return array|Buildable[]
     */
    public function getQueued()
    {
        return $this->queued;
    }

    /**
     * @param Buildable $buildable
     */
    protected function queue(Buildable $buildable)
    {
        $this->queued[] = $buildable;
    }

    /**
     * @param string        $method
     * @param callable|null $callback
     */
    public function macro($method, callable $callback = null)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Fluent builder should be extended with a closure argument, none given');
        }

        $this->macros[$method] = $callback;
    }

    /**
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (isset($this->macros[$method])) {

            // Add builder as first closure param, append the given params
            array_unshift($params, $this);

            return call_user_func_array($this->macros[$method], $params);
        }

        throw new InvalidArgumentException('Fluent builder method [' . $method . '] does not exist');
    }

    /**
     * Reset queued fields/relations/embeddables
     *
     * @return $this
     */
    public function resetQueued()
    {
        $this->queued = null;

        return $this;
    }
}
