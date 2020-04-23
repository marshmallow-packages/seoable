Nova.booting((Vue, router, store) => {
    Vue.component("seoable", require("./partials/Seoable.vue").default);
    Vue.component('detail-seoable', require('./components/DetailField.vue').default)
    Vue.component('form-seoable', require('./components/FormField.vue').default)
})
