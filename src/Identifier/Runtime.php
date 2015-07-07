<?php
namespace NeoStore\Identifier;

use NeoStore\IdentifierInterface;
class Runtime implements IdentifierInterface
{
    protected $identifier;

    /**
     * Get the current or new unique identifier
     * 
     * @return string The identifier
     */
    public function get()
    {
        if(!$this->identifier)
        {
            $this->regenerate();
        }
        return $this->identifier;
    }

    /**
     * Regenerate the identifier
     * 
     * @return string The identifier
     */
    public function regenerate()
    {
        $identifier = md5(uniqid(null, true));

        $this->identifier = $identifier;
    }

    /**
     * Forget the identifier
     * 
     * @return void
     */
    public function forget()
    {
        $this->identifier = null;
    }
}