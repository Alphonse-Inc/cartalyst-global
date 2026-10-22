(function () {
  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  document.querySelectorAll("[data-ajax-form]").forEach(function (form) {
    form.addEventListener("submit", function (event) {
      if (!window.fetch) return;
      event.preventDefault();
      const btn = form.querySelector("[type=submit]");
      const status = form.querySelector("[data-form-status]");
      const success = document.querySelector("[data-success-panel]");
      const body = new FormData(form);
      if (btn) btn.disabled = true;
      fetch(form.action, {
        method: "POST",
        body: body,
        headers: { "X-Requested-With": "XMLHttpRequest", Accept: "application/json" },
      })
        .then(function (res) {
          return res.json().then(function (data) {
            return { ok: res.ok, data: data };
          });
        })
        .then(function (result) {
          if (result.ok && result.data.ok) {
            if (success && result.data.reference) {
              success.hidden = false;
              const ref = success.querySelector("[data-reference]");
              if (ref) ref.textContent = result.data.reference;
              form.hidden = true;
              success.scrollIntoView({ behavior: reduced ? "auto" : "smooth", block: "center" });
            } else if (status) {
              status.textContent = result.data.message || "Sent.";
              status.className = "mt-4 text-emerald-700 font-semibold";
              form.reset();
            }
            return;
          }
          const message = (result.data && (result.data.message || Object.values(result.data.errors || {})[0])) || "Please check the form.";
          if (status) {
            status.textContent = message;
            status.className = "mt-4 text-red-700 font-semibold";
          }
        })
        .catch(function () {
          form.submit();
        })
        .finally(function () {
          if (btn) btn.disabled = false;
        });
    });
  });

  document.querySelectorAll("[data-reveal]").forEach(function (el) {
    if (reduced) {
      el.classList.add("opacity-100");
      return;
    }
    el.classList.add("opacity-0", "translate-y-4", "transition", "duration-700");
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.remove("opacity-0", "translate-y-4");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    io.observe(el);
  });
})();

function procurementForm() {
  return {
    step: 1,
    desktop: window.matchMedia("(min-width: 1024px)").matches,
    init() {
      const mq = window.matchMedia("(min-width: 1024px)");
      const self = this;
      const apply = function () { self.desktop = mq.matches; };
      if (mq.addEventListener) mq.addEventListener("change", apply);
      else mq.addListener(apply);
    },
    show(n) {
      return this.desktop || this.step === n;
    },
    next() {
      if (this.step < 3) this.step += 1;
    },
    prev() {
      if (this.step > 1) this.step -= 1;
    },
  };
}
