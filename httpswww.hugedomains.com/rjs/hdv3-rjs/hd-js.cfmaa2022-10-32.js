
/* {ts '2023-09-13 20:27:37'} */
function getRecaptchaToken(a, tt, f) {
var a;
if (a == '') {
a = 'default';
}
grecaptcha.enterprise.ready(async () => {
const token = await grecaptcha.enterprise.execute('6LdRB9UiAAAAABaf3jRLyU_gwaGIp-3OvR51myRx', {action: a});
document.getElementById(tt).value = token;
window[f]();
});
}
