import MaskedPattern, { type MaskedPatternOptions } from '../../src/masked/pattern';
import { type MaskedRangeOptions } from '../../src/masked/range';
import type Masked from '../../src/masked/base';
import { type AppendFlags } from '../../src/masked/base';
export type DateMaskType = DateConstructor;
type DateOptionsKeys = 'pattern' | 'min' | 'max' | 'autofix';
export type DateValue = Date | null;
export type MaskedDateOptions = Omit<MaskedPatternOptions<DateValue>, 'mask'> & Partial<Pick<MaskedDate, DateOptionsKeys>> & {
    mask?: string | DateMaskType;
};
/** Date mask */
export default class MaskedDate extends MaskedPattern<DateValue> {
    static GET_DEFAULT_BLOCKS: () => {
        [k: string]: MaskedRangeOptions;
    };
    static DEFAULTS: {
        mask: DateConstructor;
        pattern: string;
        format: (date: DateValue, masked: Masked) => string;
        parse: (str: string, masked: Masked) => DateValue;
        lazy: boolean;
        placeholderChar: string;
        skipInvalid?: boolean | undefined;
    };
    static extractPatternOptions(opts: Partial<MaskedDateOptions>): Partial<Omit<MaskedDateOptions, 'mask' | 'pattern'> & {
        mask: MaskedPatternOptions['mask'];
    }>;
    /** Pattern mask for date according to {@link MaskedDate#format} */
    pattern: string;
    /** Start date */
    min?: Date;
    /** End date */
    max?: Date;
    /** Format typed value to string */
    format: (value: DateValue, masked: Masked) => string;
    /** Parse string to get typed value */
    parse: (str: string, masked: Masked) => DateValue;
    constructor(opts?: MaskedDateOptions);
    updateOptions(opts: Partial<MaskedDateOptions>): void;
    _update(opts: Partial<MaskedDateOptions>): void;
    doValidate(flags: AppendFlags): boolean;
    /** Checks if date is exists */
    isDateExist(str: string): boolean;
    /** Parsed Date */
    get date(): DateValue;
    set date(date: DateValue);
    get typedValue(): DateValue;
    set typedValue(value: DateValue);
    maskEquals(mask: any): boolean;
    optionsIsChanged(opts: Partial<MaskedDateOptions>): boolean;
}
export {};
//# sourceMappingURL=date.d.ts.map