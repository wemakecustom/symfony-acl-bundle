<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WMC\Symfony\AclBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class InitAclCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('acl:init')
            ->setAliases(array('init:acl'))
            ->setDescription('Mounts ACL tables in the database')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command mounts ACL tables in the database.

<info>php %command.full_name%</info>

The entity manager to use must be configured as a parameter.

Ex: <info>wmc.acl.entity_manager.name: default</info>
EOF
            )
        ;
    }

    public function getManagedClasses()
    {
        return array(
            'WMC\Symfony\AclBundle\Entity\AclClass',
            'WMC\Symfony\AclBundle\Entity\AclEntry',
            'WMC\Symfony\AclBundle\Entity\AclSecurityIdentity',
            'WMC\Symfony\AclBundle\Entity\AclTargetIdentity',
        );
    }

    protected function getMetadatas(EntityManager $em)
    {
        $metadata = array();
        foreach ($this->getManagedClasses() as $class) {
            $metadata[] = $em->getClassMetadata($class);
        }

        return $metadata;
    }

    /**
     * @see Console\Command\Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getContainer()->get('wmc.acl.entity_manager');

            $metadatas = $this->getMetadatas($em);

            if (! empty($metadatas)) {
                $saveMode = true;

                $schemaTool = new SchemaTool($em);

                $sqls = $schemaTool->getUpdateSchemaSql($metadatas, $saveMode);
                if (0 == count($sqls)) {
                    $output->writeln('Nothing to update - your database is already in sync with the current entity metadata.');

                    return;
                }

                $output->writeln('Updating database schema...');
                $schemaTool->updateSchema($metadatas, $saveMode);
                $output->writeln(sprintf('Database schema updated successfully! "<info>%s</info>" queries were executed', count($sqls)));
            } else {
                $output->writeln('No Metadata Classes to process.');
            }
        } catch (ServiceNotFoundException $e) {
            $output->writeln("<error>You need to define wmc.acl.entity_manager.</error>\nEx: <info>wmc.acl.entity_manager.name: default</info>");

            throw new \InvalidArgumentException("No wmc.acl.entity_manager defined.");
        }
    }
}
