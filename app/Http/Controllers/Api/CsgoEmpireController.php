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
        // $token = '_ga=GA1.1.437268015.1751600924; _gcl_au=1.1.1803818385.1751600924; _fbp=fb.1.1751600924533.292769433640211717; _clck=ak4mjv%7C2%7Cfxc%7C0%7C2011; CookieConsent={stamp:%27Oaqo3PODTEpVgr9vpeBhtRH9YPHcbtVrPP+QuxyTcJoKUpRLdIlxJQ==%27%2Cnecessary:true%2Cpreferences:true%2Cstatistics:true%2Cmarketing:true%2Cmethod:%27explicit%27%2Cver:1%2Cutc:1751699978305%2Cregion:%27ph%27}; data=dbae0373bd4629f613a3e5fee840a6e0; device_auth_8409548=Lz9xEkLkaM6Q2tjatPiGrygRaxXchI0aJhQmxBUfS00bYIgjYMjb41VkUIGF; __cf_bm=Jj9YKQ0gK28Qt43kLGQXeRjVRGmCQQif9wfdLg_I0ls-1752298525-1.0.1.1-_Mn2.Mhn2cMmlOZx4RVNOaeVBf4EOQZXwdGOsWYzyZ7iFuJ7Py9Loc7winZ0d2V.PbU88JGSN0kvuJLnP45UAQSp1kKJpFYn1SY4uvHsXWk; _cfuvid=mbBqRI8ubD4CWsTKiKQyNgrAjyUtQj19SsuWQbAUR1s-1752298525786-0.0.1.1-604800000; cf_clearance=qGeuXnZYqTMnEpA1AOE7dbHOBrhfTe_CN9r86sxyCnc-1752298551-1.2.1.1-.aD3zO61SPkPzfe624ei8HGorCaFGCOVhShpJQBmo8Xbxuirqp1yhn2pdMvuzhyeOQEU0IJHAzH6UJ9TjiIOJxv9msjFa5.adwbxLjIXKv5i2JhygHdC4fuzFLz2FreCL3qXRBJUp9Ic6PWDc0nyrZkI04Zk3oTxc3XC7Acv0cpLjH8pe.dtX8DQoiwwCs2ONDwQD1DeCSnREHnYNTgDLgGFSYLbiMzIzDsriCTUt0s; csgoempire=WvJFLs3SNPzgz8sfYIezmhR14O9Yi0AnIw7yfXbp; do_not_share_this_with_anyone_not_even_staff=8409548_YvYqshpoj6Hw1kkH9Tz3XbkdQHaEW8tbPAuSTnqPleyseA0JVejFVAuYMkWK; _ga_DHPQBHR4YL=GS2.1.s1752298844$o21$g1$t1752298870$j34$l0$h0; env_class=blue';

        // $referrals = Http::withHeaders([
        //     'Cookie' => "auth_token={$token}",
        // ])
        //     ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

        $apiKey = "9b971e568b93f963ec18d999fb9b1076";

        $token = '_ga=GA1.1.437268015.1751600924; _gcl_au=1.1.1803818385.1751600924; _fbp=fb.1.1751600924533.292769433640211717; _clck=ak4mjv%7C2%7Cfxc%7C0%7C2011; CookieConsent={stamp:%27Oaqo3PODTEpVgr9vpeBhtRH9YPHcbtVrPP+QuxyTcJoKUpRLdIlxJQ==%27%2Cnecessary:true%2Cpreferences:true%2Cstatistics:true%2Cmarketing:true%2Cmethod:%27explicit%27%2Cver:1%2Cutc:1751699978305%2Cregion:%27ph%27}; data=dbae0373bd4629f613a3e5fee840a6e0; device_auth_8409548=Lz9xEkLkaM6Q2tjatPiGrygRaxXchI0aJhQmxBUfS00bYIgjYMjb41VkUIGF; __cf_bm=Jj9YKQ0gK28Qt43kLGQXeRjVRGmCQQif9wfdLg_I0ls-1752298525-1.0.1.1-_Mn2.Mhn2cMmlOZx4RVNOaeVBf4EOQZXwdGOsWYzyZ7iFuJ7Py9Loc7winZ0d2V.PbU88JGSN0kvuJLnP45UAQSp1kKJpFYn1SY4uvHsXWk; _cfuvid=mbBqRI8ubD4CWsTKiKQyNgrAjyUtQj19SsuWQbAUR1s-1752298525786-0.0.1.1-604800000; cf_clearance=qGeuXnZYqTMnEpA1AOE7dbHOBrhfTe_CN9r86sxyCnc-1752298551-1.2.1.1-.aD3zO61SPkPzfe624ei8HGorCaFGCOVhShpJQBmo8Xbxuirqp1yhn2pdMvuzhyeOQEU0IJHAzH6UJ9TjiIOJxv9msjFa5.adwbxLjIXKv5i2JhygHdC4fuzFLz2FreCL3qXRBJUp9Ic6PWDc0nyrZkI04Zk3oTxc3XC7Acv0cpLjH8pe.dtX8DQoiwwCs2ONDwQD1DeCSnREHnYNTgDLgGFSYLbiMzIzDsriCTUt0s; csgoempire=WvJFLs3SNPzgz8sfYIezmhR14O9Yi0AnIw7yfXbp; do_not_share_this_with_anyone_not_even_staff=8409548_YvYqshpoj6Hw1kkH9Tz3XbkdQHaEW8tbPAuSTnqPleyseA0JVejFVAuYMkWK; _ga_DHPQBHR4YL=GS2.1.s1752298844$o21$g1$t1752298870$j34$l0$h0; env_class=blue';

        $referrals = Http::withHeaders([
            'Cookie' => "auth_token={$token}",
            // 'Authorization' => 'Bearer 9b971e568b93f963ec18d999fb9b1076',
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
