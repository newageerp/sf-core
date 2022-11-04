import React from 'react'
import { InputSize } from './OldInputField';


interface Props extends React.DetailedHTMLProps<React.TextareaHTMLAttributes<HTMLTextAreaElement>, HTMLTextAreaElement> {
    inputSize?: InputSize,
    autoRows?: boolean,
}


export default function OldTextarea(props: Props) {
    const className = ["bg-white", "rounded-md", "border border-gray-300 focus:outline-none focus:border-blue-500", "px-2 py-1", "text-base"];
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

    let rows = 5;
    if (props.rows) {
        if (props.autoRows) {
            if (props.value && typeof props.value === 'string') {
                rows = props.value.split("\n").length + 1;
            } else {
                rows = 2;
            }
        } else if (Number.isInteger(props.rows)) {
            // @ts-ignore
            rows = props.rows;
        }
    }

    const inputProps = {...props};
    delete inputProps.inputSize;
    delete inputProps.autoRows;

    return (
        <textarea {...inputProps} rows={rows} className={className.join(" ")} />
    )
}
