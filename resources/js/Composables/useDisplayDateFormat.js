import {useDateFormat} from "@vueuse/core";

export function useDisplayDateFormat(date) {
    const {value} = useDateFormat(date, 'MMMM D, YYYY')
    return value
}
