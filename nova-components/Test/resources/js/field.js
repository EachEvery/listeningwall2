Nova.booting((Vue, router, store) => {
    Vue.component('index-test', require('./components/IndexField'))
    Vue.component('detail-test', require('./components/DetailField'))
    Vue.component('form-test', require('./components/FormField'))
})
