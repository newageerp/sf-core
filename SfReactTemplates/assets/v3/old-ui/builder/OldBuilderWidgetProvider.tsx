import React, { Fragment, useContext } from 'react'
import { CheckUserPermissionComponent } from '../../../../config/NaeSPermissions'
import { getHookForSchema } from '../../../../UserComponents/ModelsCacheData/ModelFields'
import { ContentWidgetProps, WidgetType } from '../../utils'
import ButtonUIBuilderShowEditPopup from './OldButtonUIBuilderShowEditPopup'
import OldCrudStatusChange from './OldCrudStatusChange'

import { IUIBuilderItem, useUIBuilder } from './OldUIBuilderProvider'
import { OldUiH5 } from '../OldUiH5';
import DataList from './OldDataList'
import SchemaNameFromContext from './OldSchemaNameFromContext'
import { SchemaProvider } from './OldSchemaProvider'
import ButtonUIBuilderOpenLink from './OldButtonUIBuilderOpenLink'
import ButtonUIBuilderDoAction from './OldButtonBuilderDoAction'
import CreateWidgetBuilder from './OldCreateWidgetBuilder'
import PropetyLabel from './OldPropertyLabel'
import ViewCompactBuilderWidget from './OldViewCompactBuilderWidget'
import FilesWidgetUIBuilder from './OldFilesWidgetUIBuilder'
import UIBShowHideScopes from './OldUIBShowHideScopes'
import UIBViewButtonToElement from './OldUIBViewButtonToElement'
import UIBPropertyView from './OldUIBPropertyView'
import OldWhiteCard from '../OldWhiteCard'


export interface BuilderWidgetProviderValue {
  type?: WidgetType | string
  schema?: string
  element: any
  saveError?: any
  userState?: any
  useHook: any
}

export const BuilderWidgetProviderContext =
  React.createContext<BuilderWidgetProviderValue>({
    type: 'skip',
    element: {},
    useHook: {}
  })

export const useBuilderWidget = () => useContext(BuilderWidgetProviderContext)

export interface BuilderWidgetProviderProps extends ContentWidgetProps {
  builderId: string,
  children?: any,
}

export const BuilderWidgetProvider = ({
  type,
  schema,
  element,
  saveError,
  userState,
  options,
  children
}: BuilderWidgetProviderProps) => {
  const useHook = getHookForSchema(schema)
  const hookEl = useHook(element.id?element.id:-1);

  const rsElement = element.id === 0 ? element : hookEl

  const { config } = useUIBuilder()
  const widgetConfig = config.filter((i) => i.id === options.builderId)
  if (widgetConfig.length === 0) {
    return <Fragment />
  }

  if (!rsElement) {
    return <Fragment />
  }

  return (
    <BuilderWidgetProviderContext.Provider
      value={{
        type,
        schema,
        element: rsElement,
        saveError,
        userState,
        useHook
      }}
    >
      <Fragment>
        {widgetConfig[0].config.map((item, index) => {
          return (
            <Fragment key={'item-' + index + '-' + options.builderId}>
              {parseUIBuilderItem(item)}
            </Fragment>
          )
        })}
        {children}
      </Fragment>
    </BuilderWidgetProviderContext.Provider>
  )
}

export const parseUIBuilderItem = (item: IUIBuilderItem) => {
  const UIBComponents: any = getBuilderComponents()

  const type =
    item.type === 'HTML'
      ? item.props.tag
        ? item.props.tag
        : 'p'
      : item.type in UIBComponents
        ? UIBComponents[item.type].component
        : item.type

  const children = Array.isArray(item.children) ? (
    <Fragment>
      {item.children.map((subItem: IUIBuilderItem, index: number) => {
        return (
          <Fragment key={'sub-item-' + index}>
            {parseUIBuilderItem(subItem)}
          </Fragment>
        )
      })}
    </Fragment>
  ) : (
    item.children
  )

  return React.createElement(
    type,
    item.props,
    item.props.children ? item.props.children : children
  )
}

export const getBuilderComponents = () => {
  return ({
    HTML: {
      component: 'p',
      group: 'Text',
      title: 'HTML',
      settingsComponent: Fragment
    },
    UIH5: {
      component: OldUiH5,
      group: 'Typography',
      title: 'H5',
      settingsComponent: Fragment
    },

    CARDWHITE: {
      component: OldWhiteCard,
      group: 'Card',
      title: 'White',
      settingsComponent: Fragment
    },
    DATALIST: {
      component: DataList,
      group: 'Widget',
      title: 'List',
      settingsComponent: Fragment
    },
    SCHEMANAMECONTEXT: {
      component: SchemaNameFromContext,
      group: 'Data',
      title: 'Schema name (Context)',
      settingsComponent: Fragment
    },

    SCHEMAPROVIDER: {
      component: SchemaProvider,
      group: 'Provider',
      title: 'Schema',
      settingsComponent: Fragment
    },

    BUTTONBUILDEROPENLINK: {
      component: ButtonUIBuilderOpenLink,
      group: 'Buttons',
      title: 'Open link',
      settingsComponent: Fragment
    },
    BUTTONBUILDERDOACTION: {
      component: ButtonUIBuilderDoAction,
      group: 'Buttons',
      title: 'Do action',
      settingsComponent: Fragment
    },

    CRUDCREATEBTN: {
      component: CreateWidgetBuilder,
      group: 'CRUD',
      title: 'Create widget',
      settingsComponent: Fragment
    },

    CRUDSTATUSCHANGE: {
      component: OldCrudStatusChange,
      group: 'CRUD',
      title: 'Status change list',
      settingsComponent: Fragment,
    },


    DATAPROPERTYLABEL: {
      component: PropetyLabel,
      group: 'Data',
      title: 'Property label',
      settingsComponent: Fragment
    },

    CONTENTVIEWCOMPACT: {
      component: ViewCompactBuilderWidget,
      group: 'Content',
      title: 'View compact',
      settingsComponent: Fragment
    },


    ButtonUIBuilderShowEditPopup: {
      component: ButtonUIBuilderShowEditPopup,
      group: 'Buttons',
      title: 'Open edit popup',
      settingsComponent: Fragment,
    },


    FILESWIDGET: {
      component: FilesWidgetUIBuilder,
      group: 'Widget',
      title: 'Files',
      settingsComponent: Fragment,
    },

    CONTENTSHOWHIDESCOPES: {
      component: UIBShowHideScopes,
      group: 'Content',
      title: 'Show / hide scopes',
      settingsComponent: Fragment,
    },


    VIEWBUTTONELEMENT: {
      component: UIBViewButtonToElement,
      group: 'Content',
      title: 'View Button Element',
      settingsComponent: Fragment,
    },

    PROPERTYVIEWWIDGET: {
      component: UIBPropertyView,
      group: 'Widget',
      title: 'Property view',
      settingsComponent: Fragment,
    },
    PERMISSIONSWRAP: {
      component: CheckUserPermissionComponent,
      group: "PERMISSIONS",
      title: "Check permissions",
      settingsComponent: Fragment,
    }
  })
}