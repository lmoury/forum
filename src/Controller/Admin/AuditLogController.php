<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AuditLogController extends AbstractController
{

    /**
    * @Route("/admin/audit", name="admin.auditlog")
     * @param Connection $connection
     */
    public function index(Connection $connection)
    {
        $audit= $connection->fetchAll('SELECT * FROM audit_log ORDER BY event_time DESC');
        return $this->render('admin/auditlog/index.html.twig', [
            'audit' => $audit
        ]);
    }


    /**
    * @Route("/admin/audit/delete", name="admin.auditlog.delete")
     * @param Connection $connection
     */
    public function delete(Connection $connection)
    {
        $delete= $connection->exec('DELETE FROM audit_log');
        return $this->redirectToRoute('admin.auditlog');
    }

}
