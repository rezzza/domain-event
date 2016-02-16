<?php

namespace Rezzza\DomainEvent\Infra\Persistence\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;

abstract class ORMRepository
{
    /** @var ManagerRegistry */
    private $doctrine;

    /** @var string */
    private $entityClassName;

    /**
     * @param ManagerRegistry $doctrine
     * @param string $entityClassName
     */
    public function __construct(ManagerRegistry $doctrine, $entityClassName)
    {
        $this->doctrine = $doctrine;
        $this->entityClassName = $entityClassName;
    }

    protected function getManager()
    {
        return $this->doctrine->getManagerForClass($this->entityClassName);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getInternalRepository()
    {
        return $this->getManager()->getRepository($this->entityClassName);
    }
}
