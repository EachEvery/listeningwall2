Nova.booting((Vue, router, store) => {
    Vue.component('index-poemcard', require('./components/IndexField'))
    Vue.component('detail-poemcard', require('./components/DetailField'))
    Vue.component('form-poemcard', require('./components/FormField'))
})
