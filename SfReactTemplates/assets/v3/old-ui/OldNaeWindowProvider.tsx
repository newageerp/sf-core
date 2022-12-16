import React, { useContext, Fragment, useState, useEffect } from 'react'
import { useLocation } from '@newageerp/v3.templates.templates-core'
import { editPopupBySchemaAndType } from '../edit/EditPopup'

import MainView from '../pages/MainView'


export interface NaeWindowProviderValue {
    showViewPopup: (props: MvcViewModalContentProps) => void
    closeViewPopup: () => void

    showEditPopup: (props: MvcEditModalContentProps) => void
    closeEditPopup: () => void

    viewProps: MvcViewModalContentProps
    editProps: MvcEditModalContentProps

    hasActivePopup: boolean,
}

const defviewProps: MvcViewModalContentProps = {
    id: 0,
    schema: '',
    popupId: ''
}

export const NaeWindowContext = React.createContext<NaeWindowProviderValue>({
    showViewPopup: () => { },
    closeViewPopup: () => { },

    showEditPopup: () => { },
    closeEditPopup: () => { },

    viewProps: defviewProps,
    editProps: defviewProps,

    hasActivePopup: false,
})

export const useNaeWindow = () => useContext(NaeWindowContext)

export interface NaeWindowProviderProps {
    children: any
}

export const NaeWindowProvider = ({ children }: NaeWindowProviderProps) => {
    let location = useLocation()

    useEffect(() => {
        if (viewProps !== null) {
            closeViewPopup()
        }
        if (editProps !== null) {
            closeEditPopup()
        }
    }, [location.pathname])

    const [viewProps, setViewProps] = useState<MvcViewModalContentProps | null>(
        null
    )
    const [editProps, setEditProps] = useState<MvcEditModalContentProps | null>(
        null
    )

    const showViewPopup = (props: MvcViewModalContentProps) => {
        setViewProps(props)
    }
    const closeViewPopup = () => {
        setViewProps(null)
    }

    const showEditPopup = (props: MvcEditModalContentProps) => {
        const _props: MvcEditModalContentProps = props.onSaveCallback
            ? props
            : { ...props, onSaveCallback: closeEditPopup }
        setEditProps(_props)
        return () => {
            setEditProps(null)
        }
    }
    const closeEditPopup = () => {
        setEditProps(null)
    }

    return (
        <NaeWindowContext.Provider
            value={{
                showViewPopup,
                closeViewPopup,

                showEditPopup,
                closeEditPopup,

                viewProps: viewProps ? viewProps : defviewProps,
                editProps: editProps ? editProps : defviewProps,

                hasActivePopup: !!viewProps || !!editProps
            }}
        >
            <Fragment>
                {children}
                {!!viewProps && (
                    <Fragment>
                        {viewPopupBySchemaAndType(viewProps.schema, viewProps.type ? viewProps.type : 'main', {
                            onClose: closeViewPopup,
                            viewProps: viewProps
                        })}
                    </Fragment>
                )}
                {!!editProps && (
                    <Fragment>
                        {editPopupBySchemaAndType(editProps.schema, editProps.type ? editProps.type : 'main', {
                            onClose: closeEditPopup,
                            editProps: editProps,
                            setViewProps: setViewProps,
                        })}
                    </Fragment>
                )}
            </Fragment>
        </NaeWindowContext.Provider>
    )
}

const viewPopupBySchemaAndType = (schema: string, type: string, props: any) => {
    return <MainView isPopup={true} schema={schema} type={type} id={props.viewProps.id} onBack={props.onClose} />
}

// WINDOW
export interface MvcViewModalProps extends MvcViewModalContentProps {
  isOpen: boolean
  onClose: () => void
}

export interface MvcViewModalContentProps {
  schema: string
  id: string | number
  popupId?: string
  type?: string
}

export interface MvcEditModalProps extends MvcEditModalContentProps {
  isOpen: boolean
  onClose: () => void
}
export interface MvcEditModalContentProps {
  schema: string
  id: string | number
  newStateOptions?: any
  onSaveCallback?: (el: any, backFunc: any) => void
  parentElement?: any
  type?: string
  skipHiddenCheck?: boolean,
  fieldsToReturnOnSave?: string[],
}
