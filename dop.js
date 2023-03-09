document.getElementById("sent").style.display = "none";
document.getElementById("form").addEventListener("submit", function (e) {
    e.preventDefault();
    let res = "";
    const FName = document.getElementById("FName").value;
    const LName = document.getElementById("LName").value;
    const Email = document.getElementById("Email").value;
    const Phone = document.getElementById("Phone").value;
    let PhoneFul = "";
    if (Phone.length >= 17) {
        for (let i = 0; i < Phone.length; i++) {
            if (Number(Phone[i]) || Phone[i] === "0") {
                PhoneFul += Phone[i];
            }
            if (i + 1 === Phone.length) {
                document.getElementById("no_sent").innerHTML = "";
                document.getElementById("submit").style.display = "none";
                document.getElementById("sent").style.display = "flex";

                $.ajax({
                    method: "post",
                    url: "fff.php",
                    data: {
                        FName: FName,
                        LName: LName,
                        Email: Email,
                        Phone: PhoneFul,
                    },
                    success: (response) => {
                        if (response != 1) {
                            setTimeout(() => {
                                document.getElementById("form").style.display =
                                    "none";
                                document.getElementById(
                                    "form_true_db"
                                ).style.display = "flex";
                            }, 2000);
                        } else {
                            setTimeout(() => {
                                document.getElementById("form").style.display =
                                    "none";
                                document.getElementById(
                                    "form_true"
                                ).style.display = "flex";
                                KTracking.reportConversion(0, "lead");
                            }, 2000);
                        }
                    },
                });
                break;
            }
        }
    } else {
        document.getElementById("sent").innerHTML = "";
        document.getElementById("no_sent").innerHTML = "Number is not true";
    }
});

// вспомогательный код
let countryCode = "";
fetch("https://ipapi.co/json/")
    .then((d) => d.json())
    .then((d) => {
        countryCode = d.country_calling_code;
    });

var $jscomp = $jscomp || {};
$jscomp.scope = {};
$jscomp.arrayIteratorImpl = function (a) {
    var b = 0;
    return function () {
        return b < a.length ? { done: !1, value: a[b++] } : { done: !0 };
    };
};
$jscomp.arrayIterator = function (a) {
    return { next: $jscomp.arrayIteratorImpl(a) };
};
$jscomp.makeIterator = function (a) {
    var b =
        "undefined" != typeof Symbol && Symbol.iterator && a[Symbol.iterator];
    return b ? b.call(a) : $jscomp.arrayIterator(a);
};
document.addEventListener("DOMContentLoaded", function () {
    var a = function (e) {
            var c = e.target,
                n = c.dataset.phoneClear;
            c = (c = c.dataset.phonePattern)
                ? c
                : `${countryCode}(___) ___-__-___`;
            var g = 0,
                k = c.replace(/\D/g, ""),
                d = e.target.value.replace(/\D/g, "");
            "false" !== n &&
            "blur" === e.type &&
            d.length < c.match(/([_\d])/g).length
                ? (e.target.value = "")
                : (k.length >= d.length && (d = k),
                  (e.target.value = c.replace(/./g, function (l) {
                      return /[_\d]/.test(l) && g < d.length
                          ? d.charAt(g++)
                          : g >= d.length
                          ? ""
                          : l;
                  })));
        },
        b = document.querySelectorAll("[data-phone-pattern]");
    b = $jscomp.makeIterator(b);
    for (var f = b.next(); !f.done; f = b.next()) {
        f = f.value;
        for (
            var m = $jscomp.makeIterator(["input", "blur", "focus"]),
                h = m.next();
            !h.done;
            h = m.next()
        )
            f.addEventListener(h.value, a);
    }
});
