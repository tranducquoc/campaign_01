<template lang="html">
    <div>
        <input class="form-control" name="birthday" :value="value" :placeholder="placeholder" />
    </div>
</template>

<script>
import { EventBus } from '../../EventBus.js'
export default {
    props: {
        date: {
            type: String
        },
        data: {
            type: Object
        },
        formatStand: {
            type: String
        }
    },
    data() {
        return {
            value: !this.date ? this.date : window.moment(this.date).format('L'),
            standTime: this.date,
            placeholder: window.moment().format('L')
        }
    },
    created() {
        EventBus.$on('changeLanguage', (data) => {
            if (this.value != '') {
                this.emitValue(window.moment(this.standTime).format('L'))
            }
        })
    },
    mounted() {
        $(this.$el).daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'L'
            },
            ...this.data,
        });

        $(this.$el).on('apply.daterangepicker', (ev, picker) => {
            const { format } = picker.locale
            const { date } = picker.startDate._d

            this.standTime = picker.startDate.format('YYYY-MM-DD')
            this.emitValue(picker.startDate.format(format))
        });

        // set null value when clear datetimepicker
        $(this.$el).on('hide.daterangepicker', (ev, picker) => {
            this.$emit('update:date', '')
        })
    },
    methods: {
        emitValue(value) {
            this.$emit('update:date', value)
            this.$emit('input', value)
            this.value = value
            this.$emit('update:formatStand', this.standTime)
        }
    },
    beforeDestroy() {
        $(this.$el).data('daterangepicker').remove()
    }
}
</script>
