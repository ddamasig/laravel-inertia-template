import {useDateFormat} from "@vueuse/core";

export function useDisplayDateFormat(date) {
    const {value} = useDateFormat(date, 'MMM D, YYYY')
    return value
}
