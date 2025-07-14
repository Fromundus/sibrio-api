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

        // $token = '_ga=GA1.1.437268015.1751600924; _gcl_au=1.1.1803818385.1751600924; _fbp=fb.1.1751600924533.292769433640211717; _clck=ak4mjv%7C2%7Cfxc%7C0%7C2011; CookieConsent={stamp:%27Oaqo3PODTEpVgr9vpeBhtRH9YPHcbtVrPP+QuxyTcJoKUpRLdIlxJQ==%27%2Cnecessary:true%2Cpreferences:true%2Cstatistics:true%2Cmarketing:true%2Cmethod:%27explicit%27%2Cver:1%2Cutc:1751699978305%2Cregion:%27ph%27}; data=dbae0373bd4629f613a3e5fee840a6e0; device_auth_8409548=Lz9xEkLkaM6Q2tjatPiGrygRaxXchI0aJhQmxBUfS00bYIgjYMjb41VkUIGF; __cf_bm=Jj9YKQ0gK28Qt43kLGQXeRjVRGmCQQif9wfdLg_I0ls-1752298525-1.0.1.1-_Mn2.Mhn2cMmlOZx4RVNOaeVBf4EOQZXwdGOsWYzyZ7iFuJ7Py9Loc7winZ0d2V.PbU88JGSN0kvuJLnP45UAQSp1kKJpFYn1SY4uvHsXWk; _cfuvid=mbBqRI8ubD4CWsTKiKQyNgrAjyUtQj19SsuWQbAUR1s-1752298525786-0.0.1.1-604800000; cf_clearance=qGeuXnZYqTMnEpA1AOE7dbHOBrhfTe_CN9r86sxyCnc-1752298551-1.2.1.1-.aD3zO61SPkPzfe624ei8HGorCaFGCOVhShpJQBmo8Xbxuirqp1yhn2pdMvuzhyeOQEU0IJHAzH6UJ9TjiIOJxv9msjFa5.adwbxLjIXKv5i2JhygHdC4fuzFLz2FreCL3qXRBJUp9Ic6PWDc0nyrZkI04Zk3oTxc3XC7Acv0cpLjH8pe.dtX8DQoiwwCs2ONDwQD1DeCSnREHnYNTgDLgGFSYLbiMzIzDsriCTUt0s; csgoempire=WvJFLs3SNPzgz8sfYIezmhR14O9Yi0AnIw7yfXbp; do_not_share_this_with_anyone_not_even_staff=8409548_YvYqshpoj6Hw1kkH9Tz3XbkdQHaEW8tbPAuSTnqPleyseA0JVejFVAuYMkWK; _ga_DHPQBHR4YL=GS2.1.s1752298844$o21$g1$t1752298870$j34$l0$h0; env_class=blue';

        $token = 'data=bd18833c03a9ced89efca9a67060ec68; CookieConsent={stamp:%27nOY/WZ9gCGvWnxbGdZe17MeZJ3HNvsghQeyI+7G0dE1qpUCKd29C/A==%27%2Cnecessary:true%2Cpreferences:true%2Cstatistics:true%2Cmarketing:true%2Cmethod:%27explicit%27%2Cver:1%2Cutc:1726766559584%2Cregion:%27se%27}; _ga=GA1.1.1326316572.1749202421; _gcl_au=1.1.727055013.1749202421; _fbp=fb.1.1749202422191.889639979326784326; ab.storage.deviceId.d5d8a261-c279-4ad2-bcd3-ea14d9c092a2=g%3Aa063a6bc-e69e-b26a-531f-0c2bc32bbaed%7Ce%3Aundefined%7Cc%3A1749202421704%7Cl%3A1750862055001; ab.storage.userId.d5d8a261-c279-4ad2-bcd3-ea14d9c092a2=g%3A5228050%7Ce%3Aundefined%7Cc%3A1749202421695%7Cl%3A1750862055001; ab.storage.sessionId.d5d8a261-c279-4ad2-bcd3-ea14d9c092a2=g%3A8ed17e11-d166-27a6-0d6c-e4a5aecc2ee5%7Ce%3A1750870693804%7Cc%3A1750862055001%7Cl%3A1750868893804; intercom-device-id-okm1s2ii=77d183b4-0a57-4309-aa7a-283332437014; device_auth_5228050=M7dUDuN1ue4boGIlu63f1clVjM8g7C8q6En3NLCdIvYn80gFvS9D1h0VGqm9; _ga_7Q8ZMQJCT2=GS2.1.s1752235143$o46$g0$t1752235143$j60$l0$h0; csgoempire=5BYitP97GtaGRcDuJ26JKTbGtWorKDr0m8jYHYaA; do_not_share_this_with_anyone_not_even_staff=5228050_1d4peaUKSczvTaAhJgUIU412OuWG2MmoQ4Piz722dcdTGv1RiANWIKlGC9nA; _clck=14j1oq7%7C2%7Cfxl%7C0%7C1983; _cfuvid=slyPzBK3U0mV4ZDAx5TBg1FJdSf6qnJpCL5.F2lXEHs-1752486431175-0.0.1.1-604800000; __cf_bm=2yib3bpqLbr3bpUFblzonrUIHPk3W.gZVLtdDIfiyJE-1752495155-1.0.1.1-jGbkqBSiht.ZDeWoMcDKP4gZOojNszUzfoODykbv5vqIkGkl.vJgB5.7Km6kj69zWsM18f.DGQXc566sR_pWRro04fZiCelmICUttabSWcs; cf_clearance=DLcwYFfeWhwIZsXtjdidZJ_Fqdm74t6AdfS6u0HwzHI-1752495179-1.2.1.1-CLxjmFR_LPCrhkdZoEklKDJAXxTs9Vo4RKRehD1Afe.gePW1nbJYFKd_UHSMgZYCv22zQYpB9ok..ArBDeW6GvVJ5dtV.O3s8HHHCkU4dT4hIiYIk3CDD1OjeX1i.AjdGJe.gYTryrETaq3ZA07c67897g99cUJ5yh8FjVBOcVzVlNL.grDqEUTPryiZRmxhRCjIfEJc0Czo3PT8v_vRNT2wADigjzaUU5PpRm0uYks; env_class=blue; _ga_DHPQBHR4YL=GS2.1.s1752495285$o89$g1$t1752495377$j57$l0$h0; _clsk=88t0le%7C1752495378464%7C4%7C1%7Cv.clarity.ms%2Fcollect';

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
