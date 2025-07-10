<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\ReferredUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CsgoEmpireController extends Controller
{
    public function referrals(Request $request)
    {   
        $token = '_ga=GA1.1.437268015.1751600924; _gcl_au=1.1.1803818385.1751600924; _fbp=fb.1.1751600924533.292769433640211717; _clck=ak4mjv%7C2%7Cfxc%7C0%7C2011; CookieConsent={stamp:%27Oaqo3PODTEpVgr9vpeBhtRH9YPHcbtVrPP+QuxyTcJoKUpRLdIlxJQ==%27%2Cnecessary:true%2Cpreferences:true%2Cstatistics:true%2Cmarketing:true%2Cmethod:%27explicit%27%2Cver:1%2Cutc:1751699978305%2Cregion:%27ph%27}; data=dbae0373bd4629f613a3e5fee840a6e0; csgoempire=Ic9oTDFnufhbHB4aPvIH46A2MRMOfr5Sgcq3PLdg; do_not_share_this_with_anyone_not_even_staff=8409548_epwuN4lA9u21Ns2vDsVLAmdj5co3GqZAsVWo8YLNDFIhqOxYP4h1ydO8Xlbv; cf_clearance=1sh0e_.gIxTiwoerCAR0NjbKsEMZoiIM2BxhybB39wc-1751897503-1.2.1.1-GmLUuMvfBTQPCky.pDdyFaA3Bhbb8NCyOI2LtwxkK_ORRxEDyKzaNQIyJI7Ig0FHZfvpyw0OF2XkvnSGAjKqhCq93kkbaBOBPycKV4uNhKOGdnwBxsI87RutaIppKTJlBGVtBNrNfYVtf5xF_YcsUlEunrljdcivGdGvH5htAFoexdYRRzg6XbPehqksg97wzzDbkETAslSyHLq4keyPFQ6ZIXqRNj0DYEAyzi9fv2s; _ga_DHPQBHR4YL=GS2.1.s1751896419$o7$g1$t1751897714$j31$l0$h0; _cfuvid=3xfuqBSltqsuVjOVm4ayrhREkpQG1j0QIH3c2gx32ss-1751898343717-0.0.1.1-604800000; env_class=green; __cf_bm=fp7Lr6D80CHcLWiZIA7xcjQnP0vOhoS79UmDwem8VHk-1751899571-1.0.1.1-YrDO4DB.rUK7I0VONOG.T3Ju6km_4kE5VwteArIMzGx7Zp1skmLKh2yQc7ocFtfngO1sC0vUnMEJCKBXfHApCalD5sR8NvlsALJ0W_yqeTA';

        $referrals = Http::withHeaders([
            'Cookie' => "auth_token={$token}",
        ])
            ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

        return response()->json([
            "message" => "success",
            // 'user' => $auth->json()['user'] ?? null,
            // 'socket_token' => $auth->json()['socket_token'] ?? null,
            // 'socket_signature' => $auth->json()['socket_signature'] ?? null,
            "referrals" => $referrals->json(),
        ]);
    }
}
