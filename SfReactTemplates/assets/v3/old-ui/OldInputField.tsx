import React, { useEffect, useRef } from 'react'
import {useDidMount} from '@newageerp/v3.hooks.use-did-mount'

export enum InputSize {
    sm = 'sm',
    base = 'base',
    lg = 'lg'
}

interface Props extends React.DetailedHTMLProps<React.InputHTMLAttributes<HTMLInputElement>, HTMLInputElement> {
    inputSize?: InputSize | string,
    focusOnMount?: boolean;
    // value: string,
}

export const InputFieldDefClass = ["bg-white", "rounded-md", "border border-gray-300 focus:outline-none focus:border-blue-500", "px-2 py-1"]

export default function OldInputField(props: Props) {

    // const [localValue, onLocalValue] = useState<string>(props.value);
    // useEffect(() => {
    //     onLocalValue(props.value);
    // }, [props.value]);

    // const changePropValue = () => {
    //     if (props.onChange) {
    //         // @ts-ignore
    //         props.onChange({ target: { value: localValue } });
    //     }
    // }

    // const handleKeyDown = (event: any) => {
    //     if (event.key === 'Enter') {
    //         changePropValue()
    //     }
    // }

    const isMount = useDidMount();
    const inputRef = useRef(null);

    useEffect(() => {
        if (isMount && props.focusOnMount) {
            if (inputRef && inputRef.current) {
                // @ts-ignore
                inputRef.current.focus();
            }
        }
    }, [props.focusOnMount]);

    const className = [...InputFieldDefClass];
    if (props.className) {
        className.push(props.className);
    }
    const size = props.inputSize ? props.inputSize : InputSize.base
    if (size === InputSize.sm) {
        className.push('text-sm')
    } else if (size === InputSize.lg) {
        className.push('text-lg')
    } else {
        className.push('text-base')
    }

    const inputProps = { ...props };
    delete inputProps.inputSize;
    delete inputProps.focusOnMount;

    return (
        <input
            {...inputProps}
            ref={inputRef}
            className={className.join(" ")}
        // value={localValue}
        // onChange={(e) => onLocalValue(e.target.value)}
        // onBlur={changePropValue}
        // onKeyDown={handleKeyDown}
        />
    )
}
