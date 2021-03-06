<?php
 
namespace Topxia\WebBundle\Handler;

use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Topxia\Service\Common\ServiceKernel;

class LogoutSuccessHandler extends DefaultLogoutSuccessHandler
{
	public function onLogoutSuccess(Request $request)
	{

		$userPartner = ServiceKernel::instance()->getParameter('user_partner');
		if ($userPartner == 'phpwind') {
			$user = ServiceKernel::instance()->getCurrentUser();
			if (!$user->isLogin()) {
				return parent::onLogoutSuccess($request);
			}

			$url = $this->httpUtils->generateUri($request, 'partner_logout');
			$queries = array('userId' => $user['id'], 'goto' => $this->targetUrl);
			$url = $url . '?' . http_build_query($queries);
			return $this->httpUtils->createRedirectResponse($request, $url);
		}

		return parent::onLogoutSuccess($request);
	}
}