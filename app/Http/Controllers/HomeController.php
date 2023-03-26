<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use \AmoCRM\Client\AmoCRMApiClientFactory;
use \AmoCRM\OAuth\OAuthConfigInterface;
use \AmoCRM\OAuth\OAuthServiceInterface;
use  \AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use League\OAuth2\Client\Token\AccessToken;

class HomeController extends Controller
{
    function index(Request $request)
    {

        if ($request->session()->get('accessToken') !== null) {
            $apiClient = new AmoCRMApiClient(config('services.amocrm.client_id'), config('services.amocrm.secret_key'), config('services.amocrm.redirect_url'));
            $apiClient->setAccountBaseDomain("olegdenisov95.amocrm.ru");
            $apiClient->setAccessToken(new AccessToken([
                'access_token' => $request->session()->get('accessToken'),
                'refresh_token' => $request->session()->get('refreshToken'),
                'expires' => $request->session()->get('expires'),
            ]));

            $leadsService = $apiClient->leads();
            $companyService = $apiClient->companies();
            $leads = $leadsService->get();
            //dd($leads);
            echo "<pre>";

            foreach ($leads as $lead) {
                /** @var LeadModel $lead */
                if (DB::table('leads')->find($lead->getId()) === null) {
                    DB::table('leads')->insert([
                        'id' => $lead->getId(),
                        'name' => $lead->getName(),
                        'price' => $lead->getPrice(),
                        'responsible_user_id' => $lead->getResponsibleUserId(),
                        'group_id' => $lead->getGroupId(),
                        'created_by' => $lead->getCreatedBy(),
                        'updated_by' => $lead->getUpdatedBy(),
                        'updated_at' => $lead->getUpdatedAt(),
                        'account_id' => $lead->getAccountId(),
                        'pipeline_id' => $lead->getPipelineId(),
                        'status_id' => $lead->getStatusId(),
                        'closest_task_at' => $lead->getClosestTaskAt(),
                        'loss_reason_id' => $lead->getLossReasonId(),
                        'source_id' => $lead->getSourceId(),
                        'closed_at' => $lead->getClosedAt(),
                        'created_at' => $lead->getCreatedAt(),
                        'is_deleted' => $lead->getIsDeleted(),
                        'score' => $lead->getScore(),
                        'is_price_modified_by_robot' => $lead->getIsPriceModifiedByRobot(),
                    ]);
                }
                if ($lead->getTags() !== null) {
                    foreach ($lead->getTags() as $tag) {
                        /** @var TagModel $tag */
                        if (DB::table('tags')->find($tag->getId() === null)) {
                            DB::table('tags')->insert([
                                'id' => $tag->getId(),
                                'name' => $tag->getName(),
                            ]);
                        }
                        if (!DB::table('leads_tags')->where('lead_id', $lead->getId())->where('tag_id', $tag->getId())->exists()) {
                            DB::table('leads_tags')->insert([
                                'tag_id' => $tag->getId(),
                                'lead_id' => $lead->getId(),
                            ]);
                        }
                        if ($lead->getCompany() !== null)
                            if (!DB::table('tags_company')->where('company_id', $lead->getCompany()->getId())->where('tags_id', $tag->getId())->exists()) {
                                DB::table('tags_company')->insert([
                                    'tags_id' => $tag->getId(),
                                    'company_id' => $lead->getCompany()->getId(),
                                ]);
                            }
                    }
                }

                if ($lead->getCompany() !== null) {
                    if (!DB::table('leads_company')->where('company_id', $lead->getCompany()->getId())->where('lead_id', $lead->getId())->exists()) {
                        DB::table('leads_company')->insert([
                            'lead_id' => $lead->getId(),
                            'company_id' => $lead->getCompany()->getId(),
                        ]);
                    }
                    var_dump($lead->getCompany()->getId());
                    if (DB::table('company')->find($lead->getCompany()->getId()) === null) {
                        /** @var CompanyModel $company */
                        $company = $companyService->getOne($lead->getCompany()->getId());
                        var_dump($company);
                        DB::table('company')->insert([
                            'id' => $company->getId(),
                            'name' => $company->getName(),
                            'responsibleUserId' => $company->getResponsibleUserId(),
                            'groupId' => $company->getGroupId(),
                            'createdBy' => $company->getCreatedBy(),
                            'updatedBy' => $company->getUpdatedBy(),
                            'updatedAt' => $company->getUpdatedAt(),
                            'accountId' => $company->getAccountId(),
                            'closestTaskAt' => $company->getClosestTaskAt(),
                            'createdAt' => $company->getCreatedAt(),
                        ]);
                    }

                }
            }
        }

        return view('acmr');
    }
    function callback(Request $request)
    {
        try {
            $apiClient = new AmoCRMApiClient(config('services.amocrm.client_id'), config('services.amocrm.secret_key'), config('services.amocrm.redirect_url'));
            $apiClient->setAccountBaseDomain("olegdenisov95.amocrm.ru");
            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($request->query('code'));

            if (!$accessToken->hasExpired()) {
                $request->session()->put('accessToken', $accessToken->getToken());
                $request->session()->put('refreshToken', $accessToken->getRefreshToken());
                $request->session()->put('expires', $accessToken->getRefreshToken());
                // $request->session()->put('baseDomain', $accessToken->getAccountBaseDomain());
            }
        } catch (Exception $e) {
            die((string)$e);
        }
        return redirect(route('home'));
    }
}
