import React from 'react'
import { useMemo } from 'react'
import { useLocation } from 'react-router-dom';


interface Props {
    thead?: React.ReactElement
    tbody?: React.ReactElement
    tfooter?: React.ReactElement
    className?: string
    containerClassName?: string,
    id?: string,
}

export const TableBorderColor = 'gray-200'
export const TableBorder = 'tw3-border tw3-border-gray-200'

export default function OldTable(props: Props) {
    const isPrint = usePrint();
    const className = ['base-table tw3-w-full']
    if (props.className) {
        className.push(props.className)
    }

    let showOverflowClass = true;
    if (isPrint) {
        showOverflowClass = false;
    }

    return (
        <div className={`${props.containerClassName ? props.containerClassName : `tw3-w-full ${showOverflowClass ? 'tw3-overflow-x-auto' : ''}`}`}>
            <table className={className.join(' ')} id={props.id}>
                {props.thead && props.thead}
                {props.tbody && props.tbody}
                {props.tfooter && props.tfooter}
            </table>
        </div>
    )
}

export function usePrint() {
    const query = useQuery();
    const isPrint = query.get('print') === 'true';

    return isPrint;
}

export function useQuery() {
    const { search } = useLocation();

    return useMemo(() => new URLSearchParams(search), [search]);
}

export enum TableSize {
    sm = 'sm',
    base = 'base',
    lg = 'lg'
}

export interface TheadCol {
    props: TableThProps
    content: any
    keyPath?: string
    sortPath?: string
    filterPath?: string
}
export interface TableThProps
    extends React.DetailedHTMLProps<
        React.ThHTMLAttributes<HTMLTableHeaderCellElement>,
        HTMLTableHeaderCellElement
    > {
    size?: TableSize | string,
    textAlignment?: string
}